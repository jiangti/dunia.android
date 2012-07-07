<?php
class IndexController extends Model_Controller_Action {
    
	public function indexAction() {
        if ($this->isMobile() || $this->_getParam('mobile')) {
            $this->_helper->layout()->setLayout('mobile');
            $this->_helper->viewRenderer->setRender('list');
        } else {
            $this->_helper->layout->setLayout('map');
            $form = new Form_Map();
            $this->view->form = $form;

            $user = $this->_getUser();

            $this->view->lat  = $user->getLat();
            $this->view->long = $user->getLong();
            $this->view->zoom = $user->getZoom();
        }
        
        $this->view->categories = Aw_Service_Foursquare::$categoriesName;
	}
	
	public function listAction() {

    }
	
	public function locateAction() {
        $this->_populatePubs(date('D'));
	    $this->_helper->layout()->disableLayout();
	}
	
	public function pubAction() {
	    if ($id = $this->_getParam('id')) {
	        $this->view->pub = Model_DbTable_Pub::retrieveById($id);
	    }
	}
	
	public function mapAction() {
        $user = $this->_getUser();

        $this->view->lat  = $user->getLat();
        $this->view->long = $user->getLong();

        $this->_helper->layout()->setLayout('mobile-min');
	}
	
	protected function _populatePubs($day = null) {
        $service = new Service_Pub();

        $user = $this->_getUser();

        $lat  = ($user->getLat() ?: self::LAT);
        $long = ($user->getLong() ?: self::LONG);

        $lat  = $this->_getParam('lat', $lat);
        $long = $this->_getParam('long', $long);

        $this->view->pubs = $service->findPromo($lat, $long, null, $day);
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

    public function aboutAction() {

    }

    public function helpAction() {

    }
}

