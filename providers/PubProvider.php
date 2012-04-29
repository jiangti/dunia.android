<?php
class PubProvider extends Aw_Tool_Framework_ProviderAbstract {
	
	public function importMailShare() {
		$service = new Service_Share_Mail();
		$service->fetch();
	}
	
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
    
    public function buildIndexAction($coordinate) {
    	
    	$coors = explode(',', $coordinate);
    	
    	$x0 = $coors[0];
    	$y0 = $coors[1];
    	
    	$pubService = new Service_Pub_Foursquare();
    	
    	$x0  -= 0.5;
    	$y0  -= 0.5;
    	
    	$x1 = $x0 + 1;
    	$y1 = $y0 + 1;
    	
    	for ($i = $x0; $i < $x1; $i += 0.02) {
    		for ($j = $y0; $j < $y1; $j += 0.02) {
    			$pubService->latitude = $i;
    			$pubService->longitude = $j;
    			$pubService->crawlLinear();
    			echo ".";
    		}
    	}
        
    }
}
