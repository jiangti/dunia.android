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

    }
	
	public function locateAction() {
        $this->_populatePubs();
	    $this->_helper->layout()->disableLayout();
	}
	
	public function pubAction() {
	    if ($id = $this->_getParam('id')) {
	        $this->view->pub = Model_DbTable_Pub::retrieveById($id);
	    }
	}
	
	public function mapAction() {
        $this->_populatePubs();
	}
	
	protected function _populatePubs() {
        $service = new Service_Pub();

        $user = $this->_getUser();

        $lat  = ($user->getLat() ?: self::LAT);
        $long = ($user->getLong() ?: self::LONG);

        $lat  = $this->_getParam('lat', $lat);
        $long = $this->_getParam('long', $long);

        $this->view->pubs = $service->findPromo($lat, $long);
    }
	
	public function landingAction() {
        $this->_helper->layout()->setLayout('landing');
    }

    public function thankyouAction() {
        $this->_helper->layout()->setLayout('landing');

        $user = new Model_User();

        if ($email = $this->_getParam('email')) {
            $user->email = $email;
            $user->save();
        }
    }
}

