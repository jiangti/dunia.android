<?php
class PubProvider extends Aw_Tool_Framework_ProviderAbstract {
    /**
     * @param unknown_type $type
     */
    public function parse($path) {
        $files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($path) );
        $counter = 0;
        
        $csvOutput = fopen(sys_get_temp_dir() . '/scratch.csv', 'w+');
        
    	foreach($files as $file){
    		if (stripos($file->getPathName(), '/feed/') !== false) {
    			continue;
    		}
            $parser = new Model_Parser_SydneyHappyHour();
            
            $tidy = new Tidy();
			$html = $tidy->repairFile($file->getPathName());
            if ($data = $parser->parse($html)) {
            	$counter++;
            	
            	foreach ($data->promo as $line) {
            		
            		$fields = array(
            			'title' => $data->title,
            			'telephone' => $data->telephone,
            			'email' => $data->email,
            			'url' => $data->url,
            			'address' => $data->address,
            			'day' => $line->day,
            			'dealString' => $line->dealString,
            		);
            		
            		fputcsv($csvOutput, $fields);
            	
            	}
            }
    	}
    	
    	fclose($csvOutput);
    	
    	$this->put(sprintf("%d has been imported...", $counter));
    }
}