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
//    public function loginAction() {
//        $bootstrap  = $this->getInvokeArg('bootstrap');
//        $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();
//        $twitter    = $bootstrap->getPluginResource('twitter')->getTwitter();
//
//        $this->view->foursquareUrl = $foursquare->getAuthorizeUrl('http://localhost/user/login-foursquare');
//        $this->view->twitterUrl    = $twitter->getAuthenticateUrl();
//    }

    public function loginFoursquareAction() {

        if ($code = $this->_getParam('code')) {
            $bootstrap  = $this->getInvokeArg('bootstrap');
            $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

            $token = $foursquare->getAccessToken($code, 'http://localhost/user/login-foursquare');
            $this->view->accessToken = $token->access_token;
        }
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
        $bootstrap  = $this->getInvokeArg('bootstrap');
        $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

        $foursquare->setAccessToken('0P2OSBQ0MJTILVEB3WK0TNOY2QFI1EF0NTMWXA1B0PDHDMVH');

        $checkins = $foursquare->get('/users/self/checkins', array(
            'limit'      => 250,
        ));

        $totalCheckins = $checkins->response->checkins->count;
        $offset = 0;

        $data = array();
        while ($totalCheckins) {
            foreach ($checkins->response->checkins->items as $checkin) {
                if (isset($data[$checkin->venue->id])) {
                    $data[$checkin->venue->id]['count']++;
                } else {
                    $data[$checkin->venue->id] = array (
                        'count' => 1,
                        'name'  => $checkin->venue->name,
                        'icon'  => $checkin->venue->categories[0]->icon
                    );
                }
            }

            $offset += 250;
            if ($totalCheckins > 250) {
                $totalCheckins -= 250;
            } else {
                $totalCheckins = 0;
            }

            $checkins = $foursquare->get('/users/self/checkins', array(
                'limit'  => 250,
                'offset' => $offset
            ));
        }

        uasort($data, function ($a, $b)
            {
                if ($a['count'] == $b['count']) {
                    return 0;
                }
                return ($a['count'] > $b['count']) ? -1 : 1;
            });

        $this->view->data = $data;
    }
}
