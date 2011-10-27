<?php

class Dol_Model_Service_Google_Geocoder
{
    public static function geocodeAddress($address){
        $address = urlencode(trim($address));
        $http = new Zend_Http_Client('http://maps.google.com/maps/api/geocode/json?sensor=true&address=' . $address,
                                     array('maxredirects' => 0, 'timeout' => 30));
        $request = $http->request();
        return Zend_Json_Decoder::decode($request->getRawBody());
    }

}

