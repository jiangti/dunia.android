<?php
class Bootstrap extends Aw_BootstrapAbstract {
	public function _initEnv() {
	    require_once APPLICATION_ROOT . '/application/controllers/ControllerAbstract.php';

		$moduleLoader = new Zend_Application_Module_Autoloader(array(
				        'namespace' => '',
				        'basePath'  => APPLICATION_PATH));
		
		//$pluginLoader = new Zend_Loader_PluginLoader();
		//$pluginLoader->addPrefixPath('Application_Plugin', APPLICATION_ROOT . '/application/plugins/');
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
        	->appendFile( '/js/formalize/jquery.formalize.js')
        
        ;

        return $view;
    }
}
