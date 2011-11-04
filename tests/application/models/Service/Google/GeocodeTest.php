<?php
class Model_Service_Google_GeocodeTest extends PHPUnit_Framework_TestCase {

    public function testGeo() {
        $result = Aw_Service_Google_Geocoder::geocodeAddress('103 Victoria St Potts Point NSW 2011');
        
        $this->assertEquals('OK', $result['status']);
        
        $this->assertEquals('151.222724', $result['results'][0]['geometry']['location']['lng']);
        $this->assertEquals('-33.870884', $result['results'][0]['geometry']['location']['lat']);
        
    }
}
