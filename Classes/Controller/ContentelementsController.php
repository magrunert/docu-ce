<?php

declare(strict_types=1);

namespace Magrunert\DocuCe\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Http\HtmlResponse;
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

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        // get all Ctypes from getPagesTSconfig (['mod.']['wizards.']['newContentElement.']['wizardItems.'])
        $cTypes = $this->utility->getAllWizardItems();

        // get all Ctypes that are stored in database except list
        $cTypesInUse = $this->utility->getAllCtypes();

        foreach ($cTypesInUse as $item) {
            $cTypeInUse[] = $item['CType'];
        }

        $cTypesNotInUse = array_diff($cTypes, $cTypeInUse);
        asort($cTypesNotInUse);

        $elements=[];
        foreach ($cTypesInUse as $cType) {
            $elements[] = [
                'number' =>  $cType['count'],
                'ctype' =>  $cType['CType'],
                'wizardItem'    =>  $this->utility->getWizardItems($cType['CType']),
            ];
        }

        $this->view->assign('backendLayouts', $this->utility->getBackendlayouts());
        $this->view->assign('elements', $elements);
        $this->view->assign('notUsedElements', $cTypesNotInUse);
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(): ResponseInterface
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
        return $this->htmlResponse();
    }

    /**
     * action showBackendLayout
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showBackendLayoutAction(): ResponseInterface
    {
        $backendLayout = $this->request->getArguments();

        $backendLayoutConfig = BackendUtility::getPagesTSconfig(0)['mod.']['web_layout.']['BackendLayouts.'][$backendLayout['backendLayout']['key']]['config.'] ?? [];

        $this->view->assign('backendLayoutConfig', $backendLayoutConfig);
        $this->view->assign('backendLayoutTitle', $backendLayout['backendLayout']['title']);
        return $this->htmlResponse();
    }
}
