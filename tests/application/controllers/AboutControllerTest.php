<?php 

class AboutControllerTest extends ControllerTestCase { 

    public function testDoesAboutIndexPageExist() { 
        $this->dispatch('/about'); 
        $this->assertController('about'); 
        $this->assertAction('index'); 
    } 

}
