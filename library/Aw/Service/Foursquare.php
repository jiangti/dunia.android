<?php
require_once APPLICATION_ROOT . '/library/Aw/Contrib/Foursquare/EpiCurl.php';
require_once APPLICATION_ROOT . '/library/Aw/Contrib/Foursquare/EpiFoursquare.php';

class Aw_Service_Foursquare extends EpiFoursquare {
    
    const CATEGORY_PUB = '4bf58dd8d48988d11b941735';
    const CATEGORY_BAR = '4bf58dd8d48988d116941735';
    
    public $latutude, $longitude;
    
    public function __construct($clientId, $clientSecret, $accessToken = null)
    {
        return parent::__construct($clientId, $clientSecret, $accessToken);
    }
    
    /**
     * Some how async calls are not stable?
     */
	public function getLin($endpoint, $params = null)
	{
	    return $this->request('GET', $endpoint, $params);
	}
    
    private function request($method, $endpoint, $params = null)
    {
    	if(preg_match('#^https?://#', $endpoint))
    		$url = $endpoint;
    	else
    		$url = $this->getApiUrl($endpoint);
    	if($this->accessToken)
    	{
    		$params['oauth_token'] = $this->accessToken;
    	}
    	else
    	{
    		$params['client_id'] = $this->clientId;
    		$params['client_secret'] = $this->clientSecret;
    	}
    
    	if($method === 'GET')
    		$url .= is_null($params) ? '' : '?'.http_build_query($params, '', '&');
    	$ch  = curl_init($url);
    	curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	if(isset($_SERVER ['SERVER_ADDR']) && !empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] != '127.0.0.1')
    		curl_setopt($ch, CURLOPT_INTERFACE, $_SERVER ['SERVER_ADDR']);
    	if($method === 'POST' && $params !== null)
    	{
    		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    	}
    
    	$response = curl_exec($ch);
    	curl_close($ch);
    	return json_decode($response);
    }
    
    
    /*
    $clientId = 'a40b1aece83e8d94a08fff1e94f87c2f04af2881a';
    $clientSecret = 'e83c621567e6c430848db6dc5dde94b9';
    $code = 'BFVH1JK5404ZUCI4GUTHGPWO3BUIUTEG3V3TKQ0IHVRVGVHS';
    $accessToken = 'DT32251AY1ED34V5ADCTNURTGSNHWXCNTOMTQM5ANJLBLO2O';
    $redirectUri = 'http://www.jaisenmathai.com/foursquare-async/simpleTest.php';
    $userId = '5763863';
    $fsObj = new EpiFoursquare($clientId, $clientSecret, $accessToken);
    $fsObjUnAuth = new EpiFoursquare($clientId, $clientSecret);
    */
}