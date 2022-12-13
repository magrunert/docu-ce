<?php

declare(strict_types=1);

namespace Magrunert\DocuCe\Controller;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Magrunert\DocuCe\Utility\Utility;

/**
 * class ContentelementsController
 */
class ContentelementsController extends ActionController
{
    /**
     * @var Utility
     */
    protected Utility $utility;

    /**
     * @param Utility $utility
     */
    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    public function listAction(): void
    {
        // get all Ctypes except list
        // @TBD: set manually unwanted cTypes?
        $cTypes = $this->utility->getAllCtypes();

        $elements=[];
        foreach ($cTypes as $cType) {
            $elements[] = [
                'number' =>  $cType['count'],
                'ctype' =>  $cType['CType'],
                'wizardItem'    =>  $this->utility->getWizardItems($cType['CType']),
            ];
        }

        $this->view->assign('backendLayouts', $this->utility->getBackendlayouts());
        $this->view->assign('elements', $elements);
    }

    public function showAction()
    {
        $cType = $this->request->getArgument('ctype');
        $elements = $this->utility->getCtype($cType);

        // @TBD get icon of pagetype

        // Site Configuration
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        //$sites = $siteFinder->getAllSites();
        
        $i=0;
        foreach ($elements as $element) {
            $elements[$i]['scheme'] = $siteFinder->getSiteByPageId($element['page_uid'])->getBase()->getScheme();
            $elements[$i]['host'] = $siteFinder->getSiteByPageId($element['page_uid'])->getBase()->getHost();
            $elements[$i]['site'] = $siteFinder->getSiteByPageId($element['page_uid'])->getIdentifier();
            $i++;
        }

        $ceWizardItem = $this->utility->getWizardItems($cType);

        $this->view->assign('ceWizardItem', $ceWizardItem);
        $this->view->assign('ctype', $cType);
        $this->view->assign('elements', $elements);
    }

    public function showBackendLayoutAction()
    {
        $backendLayout = $this->request->getArguments();

        $backendLayoutConfig = BackendUtility::getPagesTSconfig(0)['mod.']['web_layout.']['BackendLayouts.'][$backendLayout['backendLayout']['key']]['config.'] ?? [];

        $this->view->assign('backendLayoutConfig', $backendLayoutConfig);
        $this->view->assign('backendLayoutTitle', $backendLayout['backendLayout']['title']);
    }
}
