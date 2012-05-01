<?php
/**
 *
 * @author jiangti
 *
 */
class Aw_Plugin_Fb extends Zend_Controller_Plugin_Abstract {
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $application = Zend_Registry::get('application');
        $facebook = $application->getResource('fb');
        if ($facebook->getUser()) {

            $me = $facebook->api('/me');

            if ($user = Dv_Table_User::retrieveByFacebook($facebook)) {

                $auth = Zend_Auth::getInstance();
                $auth->getStorage()->write($user);

            } elseif ($user = Dv_Table_User::retrieveByEmail($me['email'])) {




            } else {

                $request->setControllerName('fb')->setActionName('register');
            }
        }
    }
}

/**
 * if (login->facebook) {
 *     if (isUser) login, and redirect to profile
 *     else (isEmail) ask if want to link (else) use facebook regiter
 *     else use facebook register
 * }
 */