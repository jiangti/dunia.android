<?php

class Application_Controller_Helper_Search extends Zend_Controller_Action_Helper_Abstract {
    public function preDispatch() {
        $view = $this->getActionController()->view;
        $form = new Dol_Model_Forms_Search();

        $request = $this->getActionController()->getRequest();
        if ($request->isPost()) { // && $request->getPost('submitsignup')) {
            if($form->isValid($request->getPost())) {
                $data = $form->getValues();
                $searchQuery = $form->searchQuery->getValue();
				
                if (!$searchQuery) {
                	$searchQuery = '%';
                }
        		
                $searchEngine = new Dol_Model_SearchEngine();
        		$this->view->venues = $searchEngine->search($searchQuery); //To make it work for the moment
            }
        }
        
        $view->searchForm = $form;
    }
}