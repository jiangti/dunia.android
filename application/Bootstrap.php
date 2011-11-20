<?php
class Bootstrap extends Aw_BootstrapAbstract {
	public function _initEnv() {
	    require_once APPLICATION_ROOT . '/application/controllers/ControllerAbstract.php';

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
        	->appendStylesheet('/css/style.css')
        	->appendStylesheet('/css/formalize/formalize.css')
        ;

        $view
        	->headScript()
        	->appendFile('/js/jquery-1.7.min.js')
        	->appendFile('/js/formalize/jquery.formalize.js')
        ;

        return $view;
    }
}
