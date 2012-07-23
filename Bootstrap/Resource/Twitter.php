<?php
class Aw_Bootstrap_Resource_Twitter extends Zend_Application_Resource_ResourceAbstract {
    /**
     * @var Aw_Service_Foursquare
     */
    protected $_twitter;
    
    public function init() {
    	
        $options = $this->getOptions();
        $twitter = new Aw_Service_Twitter(
    	    $options['consumerKey'],
    	    $options['consumerSecret']
        );

        $this->_twitter = $twitter;

        return $twitter;
    }

    /**
     * @return Aw_Service_Twitter
     */
    public function getTwitter() {
        return $this->_twitter;
    }
}