<?php

defined('TYPO3') || die();

(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'DocuCe',
        'help',
        'listcontentelements',
        '',
        [
            \Magrunert\DocuCe\Controller\ContentelementsController::class => 'list, show, showBackendLayout',

        ],
        [
            'access' => 'admin',
            'icon'   => 'EXT:docu_ce/Resources/Public/Icons/user_mod_listcontentelements.svg',
            'labels' => 'LLL:EXT:docu_ce/Resources/Private/Language/locallang_listcontentelements.xlf',
        ]
    );
})();
