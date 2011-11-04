<?php
// Define path to application directory
defined('APPLICATION_ROOT') || define('APPLICATION_ROOT', realpath(dirname(__FILE__)));
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', APPLICATION_ROOT . '/application');
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
// Ensure library/ is on include_path
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path(),
)));

require_once __DIR__ . '/library/Aw/Util.php';