<?php

use TYPO3\CMS\Backend\Controller;

return [
    'contentelements_serve' => [
        'path' => '/contentelements/serve',
        'target' => Magrunert\DocuCe\ContentelementsServeController::class . '::mainAction'
    ],
];
