<?php

use TYPO3\CMS\Backend\Controller;

return [
    'contentelements_serve' => [
        'path' => '/contentelements/serve',
        'access' => 'public',
        'target' => \Magrunert\DocuCe\Controller\ContentelementsServeController::class . '::mainAction'
    ],
];
