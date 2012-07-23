<?php
require_once APPLICATION_ROOT . '/library/Aw/Contrib/Epi/EpiCurl.php';
require_once APPLICATION_ROOT . '/library/Aw/Contrib/Foursquare/EpiFoursquare.php';

class Aw_Service_Foursquare extends EpiFoursquare {
    
    const CATEGORY_PUB              = '4bf58dd8d48988d11b941735';
    const CATEGORY_BAR              = '4bf58dd8d48988d116941735';
    const CATEGORY_DISTILLERY       = '4e0e22f5a56208c4ea9a85a0';
    const CATEGORY_NIGHTLIFE_SPOT   = '4d4b7105d754a06376d81259';
    const CATEGORY_BEER_GARDEN      = '4bf58dd8d48988d117941735';
    const CATEGORY_BREWERY          = '4bf58dd8d48988d1d7941735';
    const CATEGORY_COCKTAIL_BAR     = '4bf58dd8d48988d11e941735';
    const CATEGORY_DIVE_BAR         = '4bf58dd8d48988d118941735';
    const CATEGORY_GAY_BAR          = '4bf58dd8d48988d1d8941735';
    const CATEGORY_HOOKAH_BAR       = '4bf58dd8d48988d119941735';
    const CATEGORY_HOTEL_BAR        = '4bf58dd8d48988d1d5941735';
    const CATEGORY_KARAOKE_BAR      = '4bf58dd8d48988d120941735';
    const CATEGORY_LOUNGE           = '4bf58dd8d48988d121941735';
    const CATEGORY_NIGHTCLUB        = '4bf58dd8d48988d11f941735';
    const CATEGORY_OTHER_NIGHTLIFE  = '4bf58dd8d48988d11a941735';
    const CATEGORY_SAKE_BAR         = '4bf58dd8d48988d11c941735';
    const CATEGORY_SPEAKEASY        = '4bf58dd8d48988d1d4941735';
    const CATEGORY_SPORTS_BAR       = '4bf58dd8d48988d11d941735';
    const CATEGORY_WHISKY_BAR       = '4bf58dd8d48988d122941735';
    const CATEGORY_WINE_BAR         = '4bf58dd8d48988d123941735';

    public static $allowedCategories = array(self::CATEGORY_BAR, self::CATEGORY_BEER_GARDEN, self::CATEGORY_BREWERY,
                                      self::CATEGORY_COCKTAIL_BAR, self::CATEGORY_DISTILLERY, self::CATEGORY_DIVE_BAR,
                                      self::CATEGORY_GAY_BAR, self::CATEGORY_HOOKAH_BAR, self::CATEGORY_HOTEL_BAR,
                                      self::CATEGORY_PUB, self::CATEGORY_NIGHTLIFE_SPOT, self::CATEGORY_KARAOKE_BAR,
                                      self::CATEGORY_LOUNGE, self::CATEGORY_NIGHTCLUB, self::CATEGORY_OTHER_NIGHTLIFE,
                                      self::CATEGORY_SAKE_BAR, self::CATEGORY_SPEAKEASY, self::CATEGORY_SPORTS_BAR,
                                      self::CATEGORY_WHISKY_BAR, self::CATEGORY_WINE_BAR);
    
    
    public static $categoriesName = array(
        self::CATEGORY_PUB             => 'Pub',
        self::CATEGORY_BAR             => 'Bar',
        self::CATEGORY_DISTILLERY      => 'Distillery',
        self::CATEGORY_NIGHTLIFE_SPOT  => 'Nightlife Spot',
        self::CATEGORY_BEER_GARDEN     => 'Beer Garden',
        self::CATEGORY_BREWERY         => 'Brewery',
        self::CATEGORY_COCKTAIL_BAR    => 'Cocktail Bar',
        self::CATEGORY_DIVE_BAR        => 'Dive Bar',
        self::CATEGORY_GAY_BAR         => 'Gay Bar',
        self::CATEGORY_HOOKAH_BAR      => 'Hookah Bar',
        self::CATEGORY_HOTEL_BAR       => 'Hotel Bar',
        self::CATEGORY_KARAOKE_BAR     => 'Karaoke Bar',
        self::CATEGORY_LOUNGE          => 'Lounge',
        self::CATEGORY_NIGHTCLUB       => 'Nightclub',
        self::CATEGORY_OTHER_NIGHTLIFE => 'Other Nightlife',
        self::CATEGORY_SAKE_BAR        => 'Sake Bar',
        self::CATEGORY_SPEAKEASY       => 'Speakeasy',
        self::CATEGORY_SPORTS_BAR      => 'Sports Bar',
        self::CATEGORY_WHISKY_BAR      => 'Whisky Bar',
        self::CATEGORY_WINE_BAR        => 'Wine Bar',
    );

    
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

    public function crawlCheckins($idUser, $fromDate = null, $limit = 250) {

        $checkinTable = new Model_DbTable_Checkin();
        $db = Zend_Db_Table::getDefaultAdapter();

        $options = array(
            'limit' => $limit
        );

        if ($fromDate) {
            $options['afterTimestamp'] = $fromDate;
        }



        $offset = 0;

        do {
            $checkins = $this->get('/users/self/checkins', $options);

            foreach ($checkins->response->checkins->items as $checkin) {
                if ($checkin->venue->id) {
                    $row = $checkinTable->createRow();

                    $row['idUser']       = $idUser;
                    $row['idPub']        = $db->fetchOne($db->select()->from(array('p' => 'pub'), array('id'))->where('idFoursquare = "' . $checkin->venue->id . '"'));
                    $row['idFoursquare'] = $checkin->venue->id;
                    $row['createdAt']    = $checkin->createdAt;

                    $row->save();
                }
            }

            $offset += $limit;
            $options['offset'] = $offset;

        } while (count($checkins->response->checkins->items) == $limit);
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