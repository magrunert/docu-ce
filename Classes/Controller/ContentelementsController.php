<?php

declare(strict_types=1);

namespace Magrunert\DocuCe\Controller;

use TYPO3\CMS\Backend\Utility\BackendUtility;
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

        // get icon of pagetype
        $i=0;
        foreach ($elements as $item) {
            $icon = $GLOBALS['TCA']['pages']['columns']['doktype']['config']['items'][$item['page_doktype']][2];
            $elements[$i]['page_icon'] = $icon;
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
