<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	public function _initEnv() {
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
				        'namespace' => '',
				        'basePath'  => APPLICATION_PATH));
	} 

    public function _initView() {
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle("It's Business  Time");

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        $view
        	->headLink()
        ;

        $view
        	->headScript()
        	->appendFile('/js/jquery-1.7.min.js')
        ;
        
        return $view;
    }
}
