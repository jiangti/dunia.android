<?php
class Service_TimeTest extends PHPUnit_Framework_TestCase {
    
    public function testDataEntry() {
        
        $service = new Service_Time();
        
        $txt = '2-4';
        $service->setTime($txt);
        $this->assertEquals('14:00', $service->getStart());
        $this->assertEquals('16:00', $service->getEnd());
        
        $txt = '11am-2'; //This will be hard and probably invalid.
        $service->setTime($txt);
        $this->assertEquals('11:00', $service->getStart());
        $this->assertEquals('14:00', $service->getEnd());
        
        $txt = '10:30-4am';
        $service->setTime($txt);
        $this->assertEquals('22:30', $service->getStart());
        $this->assertEquals('23:59', $service->getEnd());
        
        $txt = '12pm-12am';
        $service->setTime($txt);
        $this->assertEquals('12:00', $service->getStart());
        $this->assertEquals('23:59', $service->getEnd());
        
        $txt = '9am-12:30pm';
        $service->setTime($txt);
        $this->assertEquals('09:00', $service->getStart());
        $this->assertEquals('12:30', $service->getEnd());
        
        $txt = '10-2';
        $service->setTime($txt);
        $this->assertEquals('22:00', $service->getStart());
        $this->assertEquals('23:59', $service->getEnd());

    }
}