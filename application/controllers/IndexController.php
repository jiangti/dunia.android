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
	    throw new Exception("Deprecated function, use the Service_Pub instead for finding and filtering.");
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
			
			$files = $form->file->getFileInfo();
			$data['files'] = $files;
			$service = new Service_Pub();
			$pub = $service->savePubFromShareArray($data, $pub);
			
			$this->_redirect(sprintf('/index/share/id/%d', $pub->id));
		}
		$this->view->form = $form;
		$this->view->pub = $pub;
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

