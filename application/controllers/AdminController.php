<?php

class AdminController extends Dol_Controller
{

	public function init()
    {
        if($this->_request->isXmlHttpRequest())
			$this->_helper->layout()->disableLayout();

        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();

        $view->registerForm = new Dol_Model_Forms_Register($this->_em);
        $view->loginForm    = new Dol_Model_Forms_Login();

        $this->view->title = 'DOL (Alpha...)';
        $this->view->windowTitle = 'DOL (Alpha...)';
    }

	public function postDispatch()
	{
		$this->_em->flush();
	}

    public function indexAction()
    {
    	$venues = $this->_em->createQuery("SELECT v FROM Dol_Model_Entity_Venue v")->getResult();
		$this->view->venues = $venues;
    	$deals = $this->_em->createQuery("SELECT d FROM Dol_Model_Entity_Deal d")->getResult();
		$this->view->deals = $deals;
    }

	public function verifyAction() {
        $this->view->venues = $this->_em->createQuery("SELECT v FROM Dol_Model_Entity_Venue v JOIN v.address a where v.verified is null and a.longitude != '' and a.latitude != ''")
                    ->setMaxResults(20)
	    		   ->getResult();
	}

}

