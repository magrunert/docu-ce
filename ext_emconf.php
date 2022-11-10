<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Documentation Content Elements',
    'description' => 'Shows all content elements that are in use on a website.',
    'category' => 'module',
    'author' => 'Marlen Grunert',
    'author_email' => 'm.grunert@supseven.at',
    'state' => 'alpha',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
