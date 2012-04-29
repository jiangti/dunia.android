<?php
class Aw_Bootstrap_Resource_Fb extends Zend_Application_Resource_ResourceAbstract {
    /**
     * @var Aw_Service_Fb
     */
    protected $_fb;
    public function init() {
    	
        $options = $this->getOptions();
        $facebook = new Aw_Service_Fb(array(
    	    'appId'  => $options['appId'],
    	    'secret' => $options['secretKey'],
    	    'cookie' => true,
        ));

        $this->_fb = $facebook;

        $name = sprintf('fbs_%s', $options['appId']);
        if( $value = idx($_COOKIE, $name) ) {
            $text = str_replace('"', '', $value);
            parse_str($text, $array);
            if ($accessToken = idx($array, 'access_token')) {
                $facebook->setAccessToken($accessToken);
            }
        }

        return $facebook;
    }

    /**
     * @return Aw_Service_Fb
     */
    public function getFb() {
        return $this->_fb;
    }
}