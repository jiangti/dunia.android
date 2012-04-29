<?php
class Aw_Tool_Framework_ProviderAbstract extends Zend_Tool_Framework_Provider_Abstract {
    protected function put($content) {
    	$this->_registry->getResponse()->appendContent($content);
    }
}
