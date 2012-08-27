<?php
class Bootstrap extends Aw_BootstrapAbstract {
	public function _initEnv() {
	    require_once APPLICATION_ROOT . '/application/controllers/ControllerAbstract.php';

		$moduleLoader = new Zend_Application_Module_Autoloader(array(
				        'namespace' => '',
				        'basePath'  => APPLICATION_PATH));
		
		Zend_Registry::set("Zend_Application", $this);
	}
	
	public function _initOptimization() {
	    $classFileIncCache = APPLICATION_ROOT . sprintf('/var/cache/pluginLoaderCache.%s.php', php_sapi_name());
	    if (file_exists($classFileIncCache)) {
	        include_once $classFileIncCache;
	    }
	    Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
	}
	
	public function _initLibraries() {
		require_once APPLICATION_ROOT . '/library/Aw/Contrib/Libphutil/src/utils/utils.php';
	}

    public function _initView() {
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle("It's Business  Time");

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        
        

        $view
        	->headScript()
        	->appendFile('http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places')
        ;
        
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
        	'partials/pagination/item.phtml'
        );
        
        return $view;
    }
    
    public function _initThumbnail() {
    	Zend_Registry::set('AW_THUMBNAIL_CACHE', APPLICATION_ROOT . '/public/cache');
    }

    protected function _initConfig() {
        Zend_Registry::set('config', $this->getOptions());
    }
    
    public function _initLog() {
        $this->bootstrap('db');
        
        
        
        $columnMapping = array(
            'priority' => 'priority',
            'message' => 'message',
            'idPub'=> 'idPub',
        );
        
        $writer1 = new Zend_Log_Writer_Db(Zend_Db_Table_Abstract::getDefaultAdapter(), 'log', $columnMapping);
        
        $log = new Zend_Log($writer1);
        $log->setEventItem('idPub', null);
        Zend_Registry::set('Logger', $log);
    }
    
}
