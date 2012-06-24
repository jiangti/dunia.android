<?php

class UserController extends Model_Controller_Action
{

    public function loginAction()
    {
        $auth = Aw_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->_redirect('/');
        }

        // Here the response of the providers are registered
        if ($this->_hasParam('provider')) {

            $user   = new Service_User();
            $result = $user->login($this->_getAllParams());

            // What to do when invalid
            if (!$result || !$result->isValid()) {
                $auth->clearIdentity($this->_getParam('provider'));
                throw new Exception('Error!!');
            } else {
                $this->_redirect('/');
            }
        } else { // Normal login page
            //$this->view->googleAuthUrl   = Aw_Auth_Adapter_Google::getAuthorizationUrl();
            $this->view->facebookAuthUrl = Aw_Auth_Adapter_Facebook::getAuthorizationUrl();
            $this->view->twitterAuthUrl  = Aw_Auth_Adapter_Twitter::getAuthorizationUrl();
        }

    }

    public function profileAction() {
        $auth = Aw_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_redirect('/user/login');
        }

    }

    public function connectAction()
    {
        $auth = Aw_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_redirect('/user/login');
        }
        $this->view->providers = $auth->getIdentity();
    }

    public function logoutAction()
    {
        Aw_Auth::getInstance()->clearIdentity();
        $session = new Zend_Session_Namespace('user');
        $session->unsetAll();
        $this->_redirect('/');
    }

    public function connectFoursquareAction() {

        if ($code = $this->_getParam('code')) {
            $bootstrap  = $this->getInvokeArg('bootstrap');
            $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();
            $session    = new Zend_Session_Namespace('user');
            $user       = $session->user;
            if ($user) {
                $user->setTable(new Model_DbTable_User());
            }

            $token = $foursquare->getAccessToken($code, 'http://127.0.0.1/user/connect-foursquare');
            $foursquare->setAccessToken($token->access_token);

            $foursquareUser = $foursquare->get('/users/self');
            $user->addService('foursquare', $token->access_token, $foursquareUser->response->user->id);
        }

        $this->_redirect('/user/profile');
    }

    public function loginTwitterAction() {
        if ($authToken = $this->_getParam('oauth_token')) {
            $bootstrap = $this->getInvokeArg('bootstrap');
            $twitter   = $bootstrap->getPluginResource('twitter')->getTwitter();

            $twitter->setToken($authToken);
            $token = $twitter->getAccessToken();

            $twitter->setToken($token->oauth_token, $token->oauth_token_secret);

            $twitterInfo = $twitter->get_accountVerify_credentials();
        }

        exit;
    }

    public function crawlCheckinsAction() {
        ini_set('max_execution_time', 2000);

        $bootstrap  = $this->getInvokeArg('bootstrap');
        $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

        $foursquare->setAccessToken('0P2OSBQ0MJTILVEB3WK0TNOY2QFI1EF0NTMWXA1B0PDHDMVH');

        $foursquare->crawlCheckins(11);
    }
}
