<?php

abstract class DOL_Controller extends Zend_Controller_Action
{
	protected $_em;

    public function preDispatch() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        
        $this->_em        = $bootstrap->getResource('doctrine');
        
        $this->_helper->viewRenderer->setViewSuffix('php');
        
        if ($this->isAjax()) {
        	$this->_helper->layout()->disableLayout();
        }
        
        parent::preDispatch();
    }
    
    public function getAuthenticatedUser() {
    	if(Zend_Auth::getInstance()->hasIdentity()) {
			return $this->_em->find('Dol_Model_Entity_User', Zend_Auth::getInstance()->getIdentity()->id);
		}
		else {
			return null;
		}
    }

    public function isAjax() {
		if($this->_request->isXmlHttpRequest()) {
			return true;
		}
		return false;
    }
}

