<?php
class Plugin_Web extends Zend_Controller_Plugin_Abstract {
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $application = Zend_Registry::get('Zend_Application');
        
        $caching = (boolean) $application->getOptionByDot('settings.caching');
        $frontendOptions = array(
                'lifetime' => 86400,
                'caching' => true,
                'debug_header' => false, // for debugging
                'default_options' => array(
                    'cache_with_get_variables' => true,
                    'cache_with_post_variables' => false,
                    'cache_with_session_variables' => true,
                    'cache_with_files_variables' => true,
                    'cache_with_cookie_variables' => true
            )
        );
        
        $backendOptions = array(
            'cache_dir' => APPLICATION_ROOT . '/var/cache'
        );
        
        $cache = Zend_Cache::factory('Page', 'File', $frontendOptions, $backendOptions);
        
        $cache->start();
        
    }
}
