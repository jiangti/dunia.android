<?php
class PubProvider extends Aw_Tool_Framework_ProviderAbstract {
	
	public function importMailShare() {
		
		$service = new Service_Share_Mail();
		if ($count = $service->fetch()) {
		}
	}
	
	public function discoverMergeAction() {
		
		$discoverTable = new Model_DbTable_Discover();
		
		$select = $discoverTable->select(true);
		$select
			->setIntegrityCheck(false)
			->joinLeft(array('p' => 'pub'), 'p.idFoursquare = discover.id', array())
			->where('p.id is null')
		;
		
		$service = new Service_Pub();
		
		$keys = array();
		
		$rows = $discoverTable->fetchAll($select);
		
		$progress = new Aw_ProgressBar(new Aw_ProgressBar_Adapter_Console(), 0, count($rows));
		
		foreach ($rows as $discover) {
			$array = $discover->toArray();
			if ($json  = json_decode($array['json'])) {
				$service->createPubFromDiscover($discover);
			}
			$progress->next();
		}
		
		$progress->finish();
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
    
    public function buildIndexAction($coordinate, $offset = 0.5) {
    	
    	$coors = explode(',', $coordinate);
    	
    	$x0 = $coors[0];
    	$y0 = $coors[1];
    	
    	$pubService = new Service_Pub_Foursquare();
    	
    	$x0  -= $offset;
    	$y0  -= $offset;
    	
    	$x1 = $x0 + 2 * $offset;
    	$y1 = $y0 + 2 * $offset;
    	
    	for ($i = $x0; $i < $x1; $i += 0.002) {
    		for ($j = $y0; $j < $y1; $j += 0.002) {
    			$pubService->latitude = $i;
    			$pubService->longitude = $j;
    			$pubService->crawlLinear();
    			echo ".";
    		}
    	}
        
    }
}
