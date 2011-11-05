<?php
class PubProvider extends Zend_Tool_Project_Provider_Abstract {
    /**
     * @param unknown_type $type
     */
    public function parse($path) {
        $files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($path) );
    	foreach($files as $file){
            $parser = new Model_Parser_SydneyHappyHour();
            $parser->parse($html);
    	}
    }
}