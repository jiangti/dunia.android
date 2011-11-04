<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initView() {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('My First Zend Framework Application');

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        $view
        	->headLink()
        	->prependStylesheet('/css/reset.css')
        	->appendStylesheet('/css/jquery/flick/jquery-ui-1.8.6.custom.css')
        ;

        $view
        	->headScript()
        	->prependFile('/js/jquery-1.4.4.min.js')
        	->appendFile('/js/jquery-ui-1.8.6.min.js')
        	->appendFile('/js/jquery.corner.js')
        	->appendFile('/js/default.js')
        	->appendFile('/js/jquery.jcarousellite.js')
        	->appendFile('/js/jquery.map-overlay.js')
        ;


        return $view;
    }
}
