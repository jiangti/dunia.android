<?php
class PubProvider extends Aw_Tool_Framework_ProviderAbstract {
	
	public function importMailShare() {
		
		$service = new Service_Share_Mail();
		if ($count = $service->fetch()) {
		}
	}
	
	public function findIndexAction($value) {
	    $service = new Service_Pub_Lucene();
	    $pubTable = new Model_DbTable_Pub();
	     
	    $docs = $service->search($value);
	    $pubs = $pubTable->find(array_map(function($a) { return $a->key; }, $docs));
	    foreach ($pubs as $pub) {
	        var_dump((string) $pub->getAddress());
	    }
	}
	
	
	public function rebuildIndexAction() {
	    $service = new Service_Pub_Lucene();
	    $pubTable = new Model_DbTable_Pub();
	    
	    $paginator = Zend_Paginator::factory($pubTable->select());
	    
	    $progressBar = new Aw_ProgressBar(new Aw_ProgressBar_Adapter_Console(), 0, $paginator->getTotalItemCount());
	    
	    $paginator->setItemCountPerPage(50);
	    
	    foreach ($paginator->getPagesInRange(0, $paginator->getTotalItemCount() / 50) as $page) {
    	    foreach ($paginator->getItemsByPage($page) as $pub) {
    	        $service->add($pub);
    	        $progressBar->next();
    	    }
	    }
	    $progressBar->finish();
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

    public function checkinsFromDiscoverAction() {

        $discoverTable = new Model_DbTable_Discover();
        $pubTable      = new Model_DbTable_Pub();
        $db            = Zend_Db_Table::getDefaultAdapter();

        $select = $db->select()
            ->from(array('d' => 'discover'), array('id', 'json'))
        ;
        $discover = $db->fetchPairs($select);

        $pubs = $pubTable->fetchAll();

        $progress = new Aw_ProgressBar(new Aw_ProgressBar_Adapter_Console(), 0, count($pubs));

        foreach ($pubs as $pub) {
            if (isset($discover[$pub->idFoursquare])) {
                $json = json_decode($discover[$pub->idFoursquare]);
                if ($json->stats->checkinsCount) {
                    $pub->checkinsCount = $json->stats->checkinsCount;
                    $pub->save();
                }
            }
            $progress->next();
        }

        $progress->finish();
    }

    public function mergeCategories() {
        $pubTable      = new Model_DbTable_Pub();
        $pubTypeTable  = new Model_DbTable_PubType();
        $discoverTable = new Model_DbTable_Discover();

        $db = Zend_Db_Table::getDefaultAdapter();

        $discover = $discoverTable->fetchAll();

        $progress = new Aw_ProgressBar(new Aw_ProgressBar_Adapter_Console(), 0, count($discover));

        foreach ($discover as $d) {
            $data = json_decode($d['json']);

            if ($data->categories) {
                $category = array_shift($data->categories);

                if ($category) {
                    $icon = null;
                    if (isset($category->icon) && isset($category->icon->prefix)) {
                        $icon = $category->icon->prefix . '64' . $category->icon->name;
                    }
                    $row = array('id'   => $category->id,
                                 'name' => $category->name,
                                 'icon' => $icon);
                    $row = $pubTypeTable->createRow($row);
                    if (!$row->isExists()) {
                        $row->save();
                    }

                    $pub = $pubTable->fetchRow($db->quoteInto('idFoursquare = ?', $d['id']));
                    if ($pub) {
                        $pub->idPubType = $category->id;
                        $pub->save();
                    }
                }
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
