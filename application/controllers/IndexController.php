<?php
class IndexController extends Model_Controller_Action {
    
	public function indexAction() {
		$this->_helper->layout->setLayout('map');
		$form = new Form_Map();
		$this->view->form = $form;
		
		$user = $this->_getUser();
		
		$this->view->lat = $user->getLat();
		$this->view->long = $user->getLong();
		
		
	}
	
	public function listAction() {
	    $this->view->pubs = $this->_getPubs($this->_getParam('latitude'), $this->_getParam('longitude'));
	}
	
	public function locateAction() {
	    $this->view->pubs = $this->_getPubs($this->_getParam('latitude'), $this->_getParam('longitude'));
	    $this->_helper->layout()->disableLayout();
	}
	
	public function pubAction() {
	    if ($id = $this->_getParam('id')) {
	        $this->view->pub = Model_DbTable_Pub::retrieveById($id);
	    }
	}
	
	public function mapAction() {
	    $this->view->pubs = $this->_getPubs($this->_getParam('latitude'), $this->_getParam('longitude'));
	}
	
	protected function _getPubs($latitude, $longitude) {
	    $pubTable = new Model_DbTable_Pub();
	    $db       = $pubTable->getAdapter();
	    $user = $this->_getUser();
	    
	    if (!$latitude) {
	        $latitude = $user->getLat();
	    }
	    if (!$longitude) {
	        $longitude = $user->getLong();
	    }
	    
	    $select = $db->select()
		    ->from(array('p' => 'pub'))
		    ->join(array('a' => 'address'), 'p.idAddress = a.id', array('longitude' => 'longitude', 'latitude', 'distance' => new Zend_Db_Expr("ROUND(6371000 * acos(cos(radians('$latitude')) * cos(radians(latitude)) * cos(radians(longitude) - radians('$longitude')) + sin(radians('$latitude')) * sin(radians(latitude))), 2)")))
		    ->order('distance');
	    
	    return $db->fetchAll($select);
	}
	
	public function shareAction() {

		$form = new Form_Deal('deal');
		
		$pub = null;
		
		if ($id = $this->_request->getParam('id')) {
			if ($pub = Model_DbTable_Pub::retrieveById($id)) {
				$form->setRecord($pub);
			}
		}
		if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
			$data = $form->getValues();
			
			$form->file->receive();
			
			$data['file_tmpfile'] = $form->file->getFileName();
			
			$service = new Service_Pub();
			$pub = $service->savePubFromShareArray($data, $pub);
			
			$this->_redirect(sprintf('/index/share/id/%d', $pub->id));
		}
		$this->view->form = $form;
	}
	
	public function foursquareAction() {
	    $bootstrap  = $this->getInvokeArg('bootstrap');
	    $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();
	    
	    $pubs = $this->_getPubs(null, null);
	    
	    $i = 0;
	    foreach ($pubs as $pub) {
	        if ($pub['longitude'] && $pub['latitude'] && $i < 10) {
	            $venue = $foursquare->get('/venues/search', array('query' => $pub['name'], 'categoryId' => '4bf58dd8d48988d11b941735', 'll' => $pub['latitude'] . ',' . $pub['longitude']));
	            echo $pub['name'] . ' ' . $pub['latitude'] . ',' . $pub['longitude'] . '<br/>';
                echo "<select>";
	            foreach ($venue->response->groups[0]->items as $item) {
	                echo '<option value="' . $item->id . '">' . $item->name . '</option>';
	            }
                echo "/<select>";
	            echo '<br/><br/>';
	            $i++;
	        }
	    }
	    exit;
	    
	    var_dump($venue->response->groups[0]->items[0]); exit;
	}
	
	public function tipsAction() {
	    $bootstrap  = $this->getInvokeArg('bootstrap');
	    $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();
	     
	    $tips = $foursquare->get('/venues/4b222120f964a520e84324e3/tips');
	    foreach ($tips->response->tips->items as $tip) {
	        echo $tip->text . '<br /><br />';
	    }
	    exit;
	}
}

