<?php

class IndexController extends Dol_Controller {

	public function init() {
        $this->view->title = 'DOL (Alpha...)';
        $this->view->windowTitle = 'DOL (Alpha...)';
        
        parent::init();
    }

	public function postDispatch() {
		$this->_em->flush();
	}

    public function loginAction() {
        if ($this->getRequest()->isPost()) {
            $adapter = new Dol_Model_Auth_Adapter_Doctrine($this->_getParam('username'), sha1($this->_getParam('password')), $this->_em);
            $result = Zend_Auth::getInstance()->authenticate($adapter);
            if (Zend_Auth::getInstance()->hasIdentity()) {
            	$user = Zend_Auth::getInstance()->getIdentity();
            	$user->lastLogin = new \DateTime('now');
            	$this->_em->persist($user);
            	$this->_em->flush();
                $this->_redirect("index");
                //$this->view->message = 'Welcome';
                exit;
            } else {
                $this->view->message = implode(' ' ,$result->getMessages());
            }
        }
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->view->form = new Dol_Model_Forms_Login();
        } else {//$this->view->message = 'Welcome ' . Zend_Auth::getInstance()->getIdentity()->username;
        	$this->_redirect("index");
        }

    }

    public function logoutAction() {
        //Log him out!!
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("index");
    }

	/**
	 * Creates a new venue
	 *
	 * @return void
	 */
	public function newVenueAction() {
		if ($this->_request->isPost()) {
			$venue = new Dol_Model_Entity_Venue($this->_getAllParams());
			$this->_em->persist($venue);
			$this->_forward('index');
		}

		$this->view->form = new Dol_Model_Forms_Venue();

	}

	public function indexAction() {

        $form = new Dol_Model_Forms_Search();
        $searchQuery = '%';
        
        if($this->_request->isPost()) {
            $post = $this->_request->getParams();

            if($form->isValid($post)) {
                $searchQuery = $form->searchQuery->getValue();
                if (!$searchQuery) {
                	$searchQuery = '%';
                }
            }
        }

        $searchEngine = new Dol_Model_SearchEngine();
        $this->view->venues = $searchEngine->search($searchQuery);
	}

	public function createAdminAction() {
	    $user = new Dol_Model_Entity_User();
        $user->username     = 'admin';
        $user->password     = sha1('admin');
        $user->emailAddress = 'test@dol.com';
        $user->firstName    = 'Dol';
        $user->lastName     = 'Admin';

        $this->_em->persist($user);
        $this->_em->flush();
        die;
	}

	public function registerAction() {
        $form = new Dol_Model_Forms_Register($this->_em);
        $this->view->form = $form;
        if($this->getRequest()->isPost()){
            if($form->isValid($_POST)){
                $data = $form->getValues();
                $data['password'] 		= sha1($data['password']);
                $data['active']   		= false;
                $data['code']	  	    = sha1(uniqid('D0l', true)); 
                $data['dateRegistered'] = new \DateTime("now");
                $user = new Dol_Model_Entity_User($data);
                $this->_em->persist($user);
                $this->_em->flush();

                // Send activation email
                
                $this->_redirect('/index');
            }
        }
        $form->populate($_POST);
	}
	
	public function activateAction() {
		$id   = $this->_getParam('id');
		$code = $this->_getParam('code');
        if ($id && $code) {
        	$user = $this->_em->find('Dol_Model_Entity_User', $id);
            if ($user) {
                if (!$user->active && $user->code == $code) {
                    $user->active = 1;
                    $this->_em->persist($user);
                    $this->_redirect('/index');
                } else {
					// Send to 404
                }
            } else {
            	// Send to 404 as well...
            }
        }
 
	}

	public function testGeocodeAction() {
	    $geocoder = new Dol_Model_Service_Google_Geocoder();
	    $result = $geocoder->geocodeAddress('103 Victoria St Potts Point NSW 2011');
	    if($result['status'] == 'OK') {
    	    if (is_array($result['results']) && count($result['results'])) {
    	        echo '103 Victoria St Potts Point NSW 2011<br/>';
    	        echo 'Long = ' . $result['results'][0]['geometry']['location']['lng'] . '<br />' . 'Lat = ' . $result['results'][0]['geometry']['location']['lat'];
    	    }
	    }
	    die;
	}
	
	public function locationAction() {
	    
	}
}

