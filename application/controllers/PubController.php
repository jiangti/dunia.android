<?php
class PubController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $pubTable = new Model_DbTable_Pub();
        
        $select = $pubTable->select();
        
        if ($query = $this->_getParam('q')) {
            $select->where('name like ?', sprintf('%s%%', $query));
        }
        
        $paginator = Zend_Paginator::factory($select);
        
        $paginator->setCurrentPageNumber($this->_request->getParam('page'))
            ->setItemCountPerPage($this->_getParam('count', 10))
        ;
        
        $this->view->pubs = $paginator;
    }
    
    public function overviewAction()
    {
        $id = $this->_getParam('id');
        
        //if (Zend_Registry::get('device')->getType() == 'mobile') {
            $this->_helper->layout()->setLayout('mobile-min');
        //}
        
        $pub = new Model_Pub();
        $this->view->pub = $pub->getById($id);
    }
    
    public function addAction()
    {
        $form = new Form_Pub();
        
        if ($this->_request->isPost()) {
            
            $post = $this->_request->getParams();
            
            if ($form->isValid($post)) {
                $pub     = new Model_Pub();
                $address = new Model_Address();
                
                $values = $form->getValues();
                
                $address->setFromArray($values['address']);
                
                $pub->setFromArray($values);
                $pub->setAddress($address);
                
                $pub->save();
            }
        } else {
            if ($idPub = $this->_getParam('id')) {
                $pub = new Model_Pub();
                
                $values = $pub->getById($idPub)->getArray();
                $form->populate($values);
                
            }
        }
        
        $this->view->form = $form;
    }
}