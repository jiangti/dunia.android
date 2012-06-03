<?php
class PubController extends Zend_Controller_Action
{
	public function init() {
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('search', 'json')->initContext();
	}
	
	public function emailAction() {
		$table = new Model_DbTable_MailShare();
		
		$id = $this->_getParam('id');
		$mailShare = Model_DbTable_MailShare::retrieveById($id);
		
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		
		$select = $table->select();
		$select
			->where('dateProcessed is null')
			->order(sprintf('FIELD(%d, `id`) desc', $mailShare->id))
		;
		
		$emailShares = $table->fetchAll($select);
		
		$forms = array();
		
		foreach ($emailShares as $emailShare) {
			
			$form = new Form_MailShare();
			$form->setAction($this->view->url(array('controller' => 'mailshare', 'action' => 'merge')));
			$form->setRecord($emailShare);
			
			$forms[] = $form;
		}
		
		
		$this->view->forms = $forms;
		
		
	}
	
	public function searchAction() {
		if ($name = $this->_getParam('term')) {
			$pubTable = new Model_DbTable_Pub();
			$rows = $pubTable->searchByName($name);
		}
		$data = array();
		foreach ($rows as $row) {
			$data[] = array(
				'id' => $row->id,
				'label' => sprintf("%s - %s", $row->name, $row->getAddress()->town),
				'value' => sprintf("%s - %s", $row->name, $row->getAddress()->town),
			);
		}
		echo json_encode($data);
		exit;
	}
	
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
            $this->_helper->viewRenderer->setRender('overview-mobile');
        }
        
        $this->_share();
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
    
    public function shareAction() {
    	$this->_share();
    }
    
    
    private function _share() {
    	$form = new Form_Deal('deal');
    	 
    	$defaults = array('name' => $this->_getParam('name'));
    	
    	$form->setDefaults($defaults);
    	 
    	$pub = null;
    	
    	if ($id = $this->_request->getParam('id')) {
    		if ($pub = Model_DbTable_Pub::retrieveById($id)) {
    			$form->setRecord($pub);
    		}
    	}
    	if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
    		$data = $form->getValues();
    	
    		$form->file->receive();
    	
    		$files = $form->file->getFileInfo();
    		$data['files'] = $files;
    		$service = new Service_Pub();
    		$pub = $service->savePubFromShareArray($data, $pub);
    	
    		$this->_redirect($this->view->url(array('id' => $pub->id)));
    	}
    	$this->view->form = $form;
    	$this->view->pubRow = $pub;
    }

    public function flagAction() {
        $this->_helper->layout()->disableLayout();

        $form  = new Form_Flag();
        $idPub = $this->_getParam('idPub');

        if ($idPub) {
            $pub = Model_DbTable_Pub::retrieveById($idPub);
            $default = array(
                'idPub'   => $idPub,
                'address' => $pub->getAddress()->toArray()
            );

            foreach ($pub->getPromos() as $index => $promo) {
                $subForm = $form->getSubForm('detail' . $index);
                $subForm->setRecord($promo);
            }

            $form->setDefaults($default);

            if ($this->_request->isPost()) {
                $flagService = new Service_Flag();
                $flag = $flagService->create($this->_request->getPost());
                $flag->save();
            }
        }
        $this->view->form = $form;
    }
}