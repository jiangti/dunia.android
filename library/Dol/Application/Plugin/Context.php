<?php

class Dol_Application_Plugin_Context extends Zend_Controller_Plugin_Abstract {
    
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $req) {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $userAgent = $bootstrap->getResource('useragent');
        $device    = $userAgent->getDevice();
        
        if ($device->getType() == 'mobile') {
            Zend_Layout::getMvcInstance()->setLayout('mobile');
        }
    }
}