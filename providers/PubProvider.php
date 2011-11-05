<?php
class PubProvider extends Zend_Tool_Project_Provider_Abstract {
    /**
     * @param unknown_type $type
     */
    public function parse($path) {
        $files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($path) );
    	foreach($files as $file){
    		if (stripos($file, '/feed/') !== false) continue;
            $parser = new Model_Parser_SydneyHappyHour();
            
            $tidy = new Tidy();
			$html = $tidy->repairFile($file->getFullPath());
            
            $parser->parse($html);
    	}
    }
}