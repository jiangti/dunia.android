<?php
class DealController extends Zend_Controller_Action {
	
    public function indexAction() {
	}
	
	public function addAction() {
	    $form  = new Form_Promo();
	    $idPub = $this->_getParam('idPub');
	    
	    if ($idPub) {
    	    if ($this->_request->isPost()) {
    	        
    	        $post = $this->_request->getParams();
    	        
    	        if ($form->isValid($post)) {
    	            $promo  = new Model_Promo();
    	            $values = $form->getValues();
    	            
    	            $promo->setFromArray($values);
    	            $promo->save();
    	            
    	            $this->_redirect('/pub/overview/id' . $idPub);
    	        }
    	    } else {
    	        if ($idPromo = $this->_getParam('id')) {
    	            $promo  = new Model_Promo();
    	            $values = $promo->getById($idPromo)->getArray();
    	            $form->populate($values);
    	        }
    	    }
            $form->getElement('idPub')->setValue($idPub);
    	    $this->view->form = $form;
	    } else {
	        // Flash message. Pub needed!
	    }
	}
}