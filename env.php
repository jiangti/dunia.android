<?php
defined('APPLICATION_ROOT') || define('APPLICATION_ROOT', realpath(dirname(__FILE__)));
defined('DOC_ROOT') || define('DOC_ROOT', APPLICATION_ROOT . '/public');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', APPLICATION_ROOT . '/application');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'jiangti'));
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path(),
)));

require_once __DIR__ . '/library/Aw/Util.php';
