<?php
$resourcesDir            = dirname(__FILE__) . '/../../data/wurfl/';

$configuration = array(
   'wurfl' => array(
       'main-file' => $resourcesDir  . 'wurfl.xml',
       'patches' => array($resourcesDir  . 'web_browsers_patch.xml'),
    ),
    'allow-reload' => true,
//    'persistence' => array(
//        'provider' => "file",
//        'params' => array(
//            'dir' => $resourcesDir  . '/cache/',
//        ),
//    ),
    'cache' => array(
        'provider' => "mysql",
        'params' => array(
            'user'     => "root",
            'db'       => "thirst_dev"
        )
    ),
);