<?php
defined('APPLICATION_ROOT') || define('APPLICATION_ROOT', realpath(dirname(__FILE__)));
defined('DOC_ROOT') || define('DOC_ROOT', APPLICATION_ROOT . '/public');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', APPLICATION_ROOT . '/application');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path(),
)));

require_once APPLICATION_ROOT . '/library/Zend/Loader/AutoloaderFactory.php';
require_once APPLICATION_ROOT . '/library/Zend/Loader/ClassMapAutoloader.php';
Zend_Loader_AutoloaderFactory::factory(array(
    'Zend_Loader_ClassMapAutoloader' => array(APPLICATION_ROOT . '/autoload_classmap.php',),
    'Zend_Loader_StandardAutoloader' => array('prefixes' => array('Zend' => APPLICATION_ROOT . '/library/Zend'),
    'fallback_autoloader' => true)
    )
);

require_once __DIR__ . '/library/Aw/Util.php';