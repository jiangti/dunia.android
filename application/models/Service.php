<?php
class Model_Service extends Aw_Model_ModelAbstract {

	public static function getAuthorizationUrl($service) {
        switch ($service) {
            case 'facebook':
                return Aw_Auth_Adapter_Facebook::getAuthorizationUrl();
            case 'twitter':
                return Aw_Auth_Adapter_Twitter::getAuthorizationUrl();
            case 'google':
                return Aw_Auth_Adapter_Google::getAuthorizationUrl();
            case 'foursquare':
                $foursquare = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getPluginResource('foursquare')->getFoursquare();
                return $foursquare->getAuthorizeUrl('http://dunia.com.au/user/connect-foursquare');
        }
	}

}