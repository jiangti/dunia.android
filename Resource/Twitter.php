<?php

class Aw_Resource_Twitter
{
	protected $_accessToken;
	protected $_options;
	
	protected $data = array();

	public function __construct($accessToken,$options)
	{
		$this->_accessToken = $accessToken;
		$this->_options = $options;
	}

    public function getAccessToken() {
        $accessToken = array(
            'oauth_token'        => $this->_accessToken->getParam('oauth_token'),
            'oauth_token_secret' => $this->_accessToken->getParam('oauth_token_secret'),
            'user_id'            => $this->_accessToken->getParam('user_id'),
            'screen_name'        => $this->_accessToken->getParam('screen_name')
        );

        return $accessToken;
    }

	public function getStatus() {
	    $endpoint = 'http://api.twitter.com/1/statuses/user_timeline.json';
		return json_decode($this->_getData('status', $endpoint));
	}

	public function getId() {
	    $profile = $this->getProfile();
		return $profile['id_str'];
	}

	public function getProfile() {
	    $endpoint = 'http://api.twitter.com/1/users/show.json';
		return (array)json_decode($this->_getData('profile', $endpoint));
	}
	
	public function getPicture() {
	    $profile = $this->getProfile();
		return $profile['profile_image_url_https'];
	}
	
	protected function _getData($label, $url)
	{
	    if (!$this->_hasData($label)) {
    	    $client = $this->_accessToken->getHttpClient($this->_options);
    	    $client->setUri($url);
    	    $client->setParameterGet('user_id', $this->_accessToken->user_id);
    	    $this->_setData($label, $client->request()->getBody());
	    }
	    return $this->data[$label];
	}
	
	protected function _setData($label, $value)
	{
	    $this->data[$label] = $value;
	}
	
	protected function _hasData($label)
	{
	    return isset($this->data[$label]) && (NULL !== $this->data[$label]);
	}

    public function getUserData() {
        $data    = array();
        $profile = $this->getProfile();

        $name = explode(' ', $profile['name']);

        $data['firstName']  = isset($name[0]) ? $name[0]: null;
        $data['lastName']   = isset($name[1]) ? $name[1]: null;
        $data['avatar']     = $this->getPicture();

        return $data;
    }
}
