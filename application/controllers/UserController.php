<?php

class UserController extends Model_Controller_Action
{

    public function indexAction() {

    }

    public function loginAction() {
        $bootstrap  = $this->getInvokeArg('bootstrap');
        $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

        $this->view->foursquareUrl = $foursquare->getAuthorizeUrl('http://localhost/user/login-foursquare');
    }

    public function loginFoursquareAction() {

        if ($code = $this->_getParam('code')) {
            $bootstrap  = $this->getInvokeArg('bootstrap');
            $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

            $token = $foursquare->getAccessToken($code, 'http://localhost/user/login-foursquare');
            $this->view->accessToken = $token->access_token;
        }
    }

}
