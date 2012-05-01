<?php
/**
 *
 * @author jiangti
 *
 */
class Aw_Plugin_Login extends Zend_Controller_Plugin_Abstract {
	/**
	 *
	 * @var Aw_Form_User_Login_Abstract
	 */
	private $_form;
    public static $publicZone = array('user' => 'logout','contact' => 'index', 'register' => 'index');

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity() && php_sapi_name() != 'cli') {
            $fb = Zend_Registry::get('application')->getResource('fb');
            if ($fb->getUser() && ($user = Dv_Table_User::retrieveByFacebook($fb))) {
                //Possibly not linked yet.
                $auth->getStorage()->write($user);
            }
        }

    }

    public function _preDispatch(Zend_Controller_Request_Abstract $request) {
        $auth = Zend_Auth::getInstance();

        if (!$auth->hasIdentity()) {

        	$form = $this->getLoginForm();


        	if ($form instanceof Aw_Form_User_Login_Abstract) {
        		if ($request->isPost() && $form->isValid($this->_request->getPost())) {
        			$username = $request->getParam('username');
        			$credential = $request->getParam('password');

        			$authAdapter = new Zend_Auth_Adapter_DbTable(Jt_Db::db(), 'user', 'username', 'password', 'sha(?)');
        			$authAdapter->setIdentity($username)->setCredential($credential);

        			$result = $authAdapter->authenticate();
        			if ($result->isValid()) {
        				$auth->getStorage()->write($authAdapter->getResultRowObject());
        			}

        		}
        	} else {
        		throw new Aw_Exception_Programmatic('You must extend the Aw_Form_User_Login_Abstract to use this plugin.');
        	}

            if (!$auth->hasIdentity() && false === (isset(self::$publicZone[$request->getControllerName()]) && self::$publicZone[$request->getControllerName()] == $request->getActionName())) {
            	$request->setControllerName('login')->setActionName('index');
            }
        } else {
            /* $acl = new Zend_Acl();
            $acl->addResource('admin');
            $acl->addResource('error');
            $acl->addRole('admin');
            $acl->addRole('vendor');
            $acl->allow('admin');
            $acl->allow('vendor')->deny('vendor', 'admin');

            $auth = Zend_Auth::getInstance();
            $data = $auth->getStorage()->read();
            $userTable = new Jt_Table_User();
            $user = $userTable->createRow((array)$data);

            if ($acl->has($request->getControllerName()) && !$acl->isAllowed($user->role(), $request->getControllerName())) {
				$request->setControllerName('error')->setActionName('permission');
            } */
        }
    }

    public function setLoginForm(Aw_Form_User_Login_Abstract $form) {
    	$this->_form = $form;
    }

    /**
     * @return Aw_Form_User_Login_Abstract
     */
    public function getLoginForm() {
    	return $this->_form;
    }
}
