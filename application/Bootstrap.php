<?php
class Bootstrap extends Aw_BootstrapAbstract {
	public function _initEnv() {
	    require_once APPLICATION_ROOT . '/application/controllers/ControllerAbstract.php';

		$moduleLoader = new Zend_Application_Module_Autoloader(array(
				        'namespace' => '',
				        'basePath'  => APPLICATION_PATH));
		
		Zend_Registry::set("Zend_Application", $this);
		//$pluginLoader = new Zend_Loader_PluginLoader();
		//$pluginLoader->addPrefixPath('Application_Plugin', APPLICATION_ROOT . '/application/plugins/');
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
        	->headLink()
        	->appendStylesheet('/css/style.css', 'screen, projection')
        	->appendStylesheet('/css/formalize/formalize.css', 'screen, projection')
        	->appendStylesheet('/contrib/jquery-ui-1.8.17.custom/css/custom-theme/jquery-ui-1.8.17.custom.css', 'screen, projection')
        ;

        $view
        	->headScript()
        	->prependFile('/contrib/jquery-ui-1.8.17.custom/js/jquery-1.7.1.min.js')
        	->appendFile( '/contrib/jquery-ui-1.8.17.custom/js/jquery-ui-1.8.17.custom.min.js')
            ->appendFile( '/js/ember-0.9.7.1.min.js')
        	->appendFile( 'http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places')
        	->appendFile( '/js/jquery.corner.js')
        	->appendFile( '/js/default.js')
        	->appendFile( '/js/jquery.sticky.js')
        	->appendFile( '/js/formalize/jquery.formalize.js')
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
    
}
