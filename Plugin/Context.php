<?php
class Aw_Plugin_Context extends Zend_Controller_Plugin_Abstract {
    
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $useragent = $bootstrap->getResource('useragent');
        $device    = $useragent->getDevice();
        Zend_Registry::set('device', $device);
        
        if ($device->getType() == 'mobile') {
            Zend_Layout::getMvcInstance()->setLayout('mobile');
        }
    }
}