<?php
class PubController extends Zend_Controller_Action
{
	public function manualAction() {
		$sql = 'SELECT pub, address, GROUP_CONCAT(DAY) FROM dirty GROUP BY pub, promo';
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$pubTable = new Model_DbTable_Pub();
		$dirtyTable = new Model_DbTable_Dirty();
		if ($this->_request->isPost()) {
			
			try {
				$db->beginTransaction();
				
				foreach ($_POST['promo'] as $promoIndex => $promo) {
					$id = explode(",", $_POST['id'][$promoIndex]);
					foreach ($dirtyTable->find($id) as $row) {
						$row->empty = '1';
						$row->save();
					}
					
					$pubs = $pubTable->find(explode(",", $_POST['idPub'][$promoIndex]));
					foreach ($pubs as $pub) {
						$pub->savePromoByParse($_POST['promo'][$promoIndex], $_POST['days'][$promoIndex]);
					}
				}
				
				$db->commit();
				
			} catch (Exception $e) {
				$db->rollBack();
				throw $e;
			}
		}
		
		if ($id = $this->_getParam('idSkip')) {
			foreach ($dirtyTable->find(explode(",", $id)) as $row) {
				$row->empty = '2';
				$row->save();
			}
		}
		
		$select = $db->select()
				->from(array('d' => 'dirty'),
					array('pub', 'address',
						'days' => new Zend_Db_Expr('GROUP_CONCAT(DAY)'),
						'id' => new Zend_Db_Expr('GROUP_CONCAT(d.id)'),
						'promo'
					)
				)
			->join(array('p' => 'pub'), 'p.name = d.pub', array('idPub' => 'id'))
			->where('d.empty = ""')
			->group('d.pub')
			->group('d.promo')
		;
		$paginator = Zend_Paginator::factory($select);
		
		$paginator->setItemCountPerPage(1)
			->setCurrentPageNumber($this->_getParam('page'))
		;
		
		
		$this->view->pager = $paginator;
	}
	
    public function indexAction()
    {
        $service = new Service_Pub();
        $select = $service->searchPub($this->_getParam('q'));
        
        $paginator = Zend_Paginator::factory($select);
        
        $paginator->setCurrentPageNumber($this->_request->getParam('page'))
            ->setItemCountPerPage($this->_getParam('count', 20))
        ;
        
        $this->view->pubs = $paginator;
    }
    
    public function overviewAction()
    {
        $id = $this->_getParam('id');
        
        $pub = new Model_Pub();
        $this->view->pub = $pub->getById($id);
        
        if (Zend_Registry::get('device')->getType() == 'mobile' || $this->_getParam('mobile')) {
            $this->_helper->layout()->setLayout('mobile-min');
            $this->view->render('pub/overview-mobile.phtml');
        }
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