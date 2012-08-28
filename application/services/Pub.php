<?php
class Service_Pub extends Aw_Service_ServiceAbstract
{
    protected $_promoFields = array('timeStart', 'timeEnd', 'price', 'liquorType', 'liquorSize');

    public function createPubFromDiscover(Model_DbTable_Row_Discover $discover) {
    	
    	$data = json_decode($discover->json);
    	
    	$goOn = false;
    	
    	foreach ($data->categories as $category) {
    		if (in_array($category->id, Aw_Service_Foursquare::$allowedCategories)) {
    			$goOn = true;
    			break;
    		}
    	}
    	
    	$lucene = new Service_Pub_Lucene();
    	
    	if ($goOn) {
	    	
	    	$db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    	
	    	try {
		    	$db->beginTransaction();
		    	
		    	$location = $data->location;
		    	
		    	$addressArray = array(
		    		'address1'  => (isset($location->address) ? $location->address : null),
		    		'city' 	    => (isset($location->city) ? $location->city : null),
		    		'postcode'  => (isset($location->postalCode) ? $location->postalCode: null),
		    		'town'      => (isset($location->city) ? $location->city : null),
		    		'state'     => (isset($location->state) ? $location->state : null),
		    		'country'   => $location->country,
		    		'latitude'  => $location->lat,
		    		'longitude' => $location->lng,
		    	);
		    	
		    	
		    	
		    	$address = Model_DbTable_Address::getRow($addressArray);
		    	
		    	$address->save();
		    	
		    	$array = array();
		    	$array['name'] 		= $data->name;
		    	$array['idAddress'] = $address->id;
		    	$array['url'] 		= (isset($data->url) ? $data->url : null);
		    	$array['idFoursquare'] = $data->id;
		    	$array['validated'] = $data->verified;
		    	$array['twitter'] 	= (isset($data->contact->twitter) ? $data->contact->twitter: null);
		    	$array['telephone'] = (isset($data->contact->phone) ? $data->contact->phone: null);

                $categories = $data->categories;
                if (is_array($data->categories)) {
                    $category = array_shift($data->categories);

                    $array['idPubType'] = $category->id;
                }
		    	
		    	$row = Model_DbTable_Pub::getRow($array);
		    	
		    	$row->save();
		    	
		    	$lucene->add($row);
		    	
		    	$db->commit();
	    		
	    	} catch (Exception $e) {
	    		
		    	$db->rollBack();
	    	}
    	
    	}
    	
    }
    
    public function fetchTips($id) {
    	
    	
    	
    	$application = Zend_Registry::get('Zend_Application');
    	$foursquare = $application->getResource('foursquare');
    	
    	$cacheManager = $application->getResource('cachemanager');
    	$tipCache = $cacheManager->getCache('f4tip');
    	
    	$url = sprintf('/venues/%s/tips', $id);
    	
    	$cacheKey = sha1($url);
    	
    	if ($tipCache->test($cacheKey)) {
    		$tips = $tipCache->load($cacheKey);
    	} else {
    		$tips = $foursquare->get($url);
    		if ($tips->code != 503) {
    			$tipCache->save($tips, $cacheKey);
    		} else {
    			throw new Exception('Foursquare servers are experiencing problems. Please retry and check status.foursquare.com for updates.');
    		}
    	}
        return $tips->response->tips->items;
    }
    
    public function savePub(Model_DbTable_Row_Pub $pub)
    {
        $lucene = new Service_Pub_Lucene();

        $notEmpty = new Zend_Validate_NotEmpty();
        if (!$notEmpty->isValid($pub->name)) {
            throw new DomainException($notEmpty->getErrors());
        }

        $table = new Model_DbTable_Pub();

        if (!$pub1 = $table->findByName($pub->name)) {
        } else {
            $pub = $pub1;
        }


        $db = $pub->getTable()->getAdapter();
        try {
            $db->beginTransaction();
            $pub->save();
            
            $lucene->add($pub);
            
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
        }


    }
    
    /**
     * @param unknown_type $data
     * array(
     *         location
     *         file0
     *         file1
     *         file2
     *         detail0 =>
     *             value
     *             start
     *            end
     *            liquorType = array()
     *            days = array()
     *
     * );
     */
    public function savePubFromShareArray($data, Model_DbTable_Row_Pub $pub = null)
    {
        $db = Model_Db::getInstance();
        
        $lucene = new Service_Pub_Lucene();
        
        try {
            
            $db->beginTransaction();
            
            if (!$pub) {
                $pub = Model_DbTable_Pub::getRow($data);
                $address = Model_DbTable_Address::createFromString($data['location']);
                $pub->setAddress($address);
                $pub->save();
                
                
            } else {
                if ($pub->id == null) {
                    throw new Exception('Pub object needs to be instantiated with persistent data.');
                }
                $pub->setFromArray($data);
                $pub->save();
                
                
                //Reset and readd all again. Easiest approach for now.
                
                $currentCount = $pub->resetPromo();
            }
            
           	$filePath = APPLICATION_ROOT . '/public/images/pub/' . $pub->id;
           	if (!file_exists($filePath)) {
           		mkdir($filePath, 0777, true);
            }
            	
            $counter = 0;
            foreach ($data['files'] as $index => $file) {
            	if ($tmpName = idx($file, 'tmp_name')) {
            		$extension = pathinfo($tmpName, PATHINFO_EXTENSION);
            		copy($tmpName, $filePath . sprintf('/upload%s.%s', $counter, $extension));
            	}
            	$counter++;
            }
            
            $count = 0;
            
            foreach ($data as $index => $value) {
                if (stripos($index, 'detail') !== false && ($value['value'] || $value['description'])) {
                    $pub->addDealFromArray($value);
                    
                    $count++;
                }
            }
            
            if ($count > $currentCount) {
                Zend_Registry::get('Logger')->info('[username] has added a promo deal.', array('idPub' => $pub->id));
            }
            
            $lucene->add($pub);
            
            $db->commit();
        } catch (Exception $e) {
            
            $db->rollback();
            
            throw $e;
        }
        
        return $pub;
    }
    
    /**
     * @param unknown_type $query
     * @return Zend_Db_Table_Select
     */
    public function searchPub($query)
    {
        $pubTable = new Model_DbTable_Pub();
        
        $select = $pubTable->select(false);
        $select->from(array('p' => 'pub'));
        
        if ($query) {
            $select->where('p.name like ?', sprintf('%s%%', $query));
            $select
            ->setIntegrityCheck(false)
            ->joinLeft(array('a' => 'address'), 'p.idAddress = a.id', array(
                'longitude',
                'latitude',
            ))
            ->orWhere(sprintf('a.address1 like "%%%s%%" or a.postcode = "%s" or a.town like "%%%s%%"', $query, $query, $query))
            ;
        }
        
        return $select;
    }
    
    public function getPubs($options) {
        $pubTable = new Model_DbTable_Pub();
        
        $select = $pubTable->select(false);
        $select->from(array('p' => 'pub'))
            ->setIntegrityCheck(false)
            ->joinLeft(
                array('a' => 'address'),
            	'p.idAddress = a.id',
                array(
        			'longitude',
                    'latitude'
                )
            )
            ->where('longitude is not null AND latitude is not null')
            ;
        
        if (isset($options['validated'])) {
            $select->where('p.validated = ?', $options['validated']);
        }
        
        return $select;
    }
    
    /**
     * @return Zend_Db_Table_Select
     */
    protected function _getPubSelect($latitude, $longitude, Model_Location_Bound $bound = null, $priority = 0) {
    	$pubTable = new Model_DbTable_Pub();
    	 
    	/**
    	 * Avoid entire table scan when we are only looking for walking distance of 4 km.
    	 */
    	
    	if ($bound) {
    	    
    		$x1 = $bound->nelat;
    		$x0 = $bound->swlat;
    		
    		$y1 = $bound->nelng;
    		$y0 = $bound->swlng;
    		
    	} else {
    	    
	    	$x0 = $latitude  - 0.05;
	    	$x1 = $latitude  + 0.05;
	    	
	    	$y0 = $longitude - 0.05;
	    	$y1 = $longitude + 0.05;
    	}
    	
    	$select = $pubTable->select()
	    	->setIntegrityCheck(false)
	    	->from(array('p' => 'pub'), array('priority' => new Zend_Db_Expr($priority), '*'))
	    	->join(array('a' => 'address'),
    			'p.idAddress = a.id',
    			array(
    				'longitude',
    				'latitude',
    				'distance' => new Zend_Db_Expr("ROUND(6371000 * acos(cos(radians('$latitude')) * cos(radians(a.latitude)) * cos(radians(a.longitude) - radians('$longitude')) + sin(radians('$latitude')) * sin(radians(a.latitude))), 2)"))
    			)
            ->joinLeft(array('pT' => 'pubType'), 'pT.id = p.idPubType', array('icon'))
	    	->where(sprintf('a.latitude between %s and %s',  $x0, $x1))
	    	->where(sprintf('a.longitude between %s and %s', $y0, $y1))
	    	->order('distance')
    	;
    	return $select;
    }

    /**
     * @param unknown_type $latitude
     * @param unknown_type $longitude
     * @return Zend_Db_Table_Select
     */
    protected function _getFindPubSelect($latitude, $longitude, $query = null, Model_Location_Bound $bound = null, $priority = 0) {
        $select = $this->_getPubSelect($latitude, $longitude, $bound, $priority);
        
        if ($query) {
        	$select
        		->where('p.name like ?', sprintf('%s%%', $query))
        		->orWhere(sprintf('a.address1 like "%%%s%%" or a.postcode = "%s" or a.town like "%%%s%%"', $query, $query, $query))
        	;
        }
        
        return $select;
    }
    
    /**
     * @return Zend_Db_Table_Select
     */
    private function _getPubsPromoSelect($latitude, $longitude, $query, $dayOfWeek, $hour, Model_Location_Bound $bound = null, $includeNoPromos = false, $options = array()) {
        
        if ($hour) {
            $hour = str_pad($hour . ':00:00', 8, '0', STR_PAD_LEFT);
        } else {
            $hour = date("H:i:00");
        }
        
        
        if (idx($options, 'day')) {
            $days = Model_Day::$days;
            $day = $days[$options['day']];
            
            $expr = new Zend_Db_Expr(sprintf("IF(find_in_set('%s', p0.day), 'later', 'none')", $day, $hour, $hour, $hour));
            
        } else {
            $day = date('D');
            
            $expr = new Zend_Db_Expr(sprintf("IF(find_in_set('%s', p0.day), CASE
                WHEN '%s' BETWEEN p0.timeStart AND p0.timeEnd THEN 'now'
                WHEN '%s' < p0.timeStart THEN 'later'
                WHEN '%s' > p0.timeEnd THEN 'earlier'
                ELSE 'none'
                END, 'none')", $day, $hour, $hour, $hour));
        }
        
        
        $joinType = 'join';
        if ($includeNoPromos) {
            $joinType = 'joinLeft';
        }
        
        $expr = new Zend_Db_Expr(sprintf("IF(find_in_set('%s', p0.day), CASE
                WHEN '%s' BETWEEN p0.timeStart AND p0.timeEnd THEN 'now'
                WHEN '%s' < p0.timeStart THEN 'later'
                WHEN '%s' > p0.timeEnd THEN 'earlier'
                ELSE 'none'
                END, 'none')", $day, $hour, $hour, $hour));
        

    	foreach (array('select1', 'select2') as $index => $var) {
    	    $$var = $this->_getFindPubSelect($latitude, $longitude, $query, $bound, $index);
    	    
    	    $$var->$joinType(array('php' => 'pubHasPromo'), 'php.idPub = p.id', array('idPub'))
        	     ->$joinType(array('p0'   => 'promo'), 'p0.id = php.idPromo',
        	            array(
        	                'timeStart' => 'DATE_FORMAT(p0.timeStart, "%H:%i")',
        	                'timeEnd'   => 'DATE_FORMAT(p0.timeEnd, "%H:%i")', 'price', 'itsOn' => $expr)
        	    )
        	    ->$joinType(array('phl' => 'promoHasLiquorType'), 'p0.id = phl.idPromo', array())
        	    ->$joinType(array('lt'  => 'liquorType'), 'lt.id = phl.idLiquorType', array('liquorType' => 'name'))
        	    ->joinLeft(array('ls'   => 'liquorSize'),  'phl.idLiquorSize = ls.id', array('liquorSize' => 'name'))
    	    ;
    	}

    	$select1->where('find_in_set(?, p0.day)', $day);
    	
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $rows = $db->fetchAll($select1);
	    $id = array_unique(ipull($rows, 'id'));
	    
	    
        if ($includeNoPromos) {
            $select2
                ->where('find_in_set(?, p0.day) = 0 OR p0.day is null', $day)
                ->where('checkinsCount > 15')
            ;
        } else {
            $select2->where('find_in_set(?, p0.day) = 0', $day);
        }

        if ($id) {
            $select2->where('p.id not in (?)', $id);
        }
        
        $select3 = $this->_getFindPubSelect($latitude, $longitude, $query, $bound, 2);
        
	    $select1->reset(Zend_Db_Select::ORDER);
	    $select2->reset(Zend_Db_Select::ORDER);
	    $select3->reset(Zend_Db_Select::ORDER);
	    $select3->where('p.checkinsCount > 150');
	    
	    $unionSelect = $select1->getTable()->select(false);
	    
	    $nulls = array();
	    
	    for ($i = 0; $i < 7; $i++) {
	        $nulls[$i] = new Zend_Db_Expr('null');
	    }
	    
	    $select3->joinLeft(array('php' => 'pubHasPromo'), 'php.idPub = p.id', $nulls)->where('php.idPub is null');

        // If we want to see a different day then do not show stuff that doesn't happen that day!
        if (strtolower($day) != strtolower(date('D')) || (isset($options['todayOnly']) && $options['todayOnly'])) {
            $select = $select1;
        } else {
            $select = $unionSelect->union(array($select1, $select2, $select3));
            $select->order('priority')->order('distance')->limit(100);
        }
	    return $select;
	    
	    
    }

    /**
     * Promo finder is always using times.
     *
     * @param unknown_type $latitude
     * @param unknown_type $longitude
     * @param string $query
     * @param string day [MON,TUE,WED...]
     * @param int 0-12
     */
    public function findPromo($latitude, $longitude, $query = null, $dayOfWeek = null, $hour = null, Model_Location_Bound $bound = null, $includeNoPromos = false, array $options = array()) {
    	
    	$table = new Model_DbTable_Pub();
    	
    	$select = $this->_getPubsPromoSelect($latitude, $longitude, $query, $dayOfWeek, $hour, $bound, $includeNoPromos, $options);
    	$data = $select->getTable()->fetchAll($select);

		return $this->_formatPromoData($data);
    }
    
    private function _formatPromoData($data) {
    	$return = array();

    	foreach ($data as $promoRow) {
    		$newPromo = array();
    		$address  = (string) $promoRow->getAddress(); //This is bad with the n+1 problem.
    	
    		$promo = $promoRow->toArray();
    		$promo['address'] = $address;
    		
    		$promo['url'] = $promoRow->getPermaLink();
    	
    		foreach ($this->_promoFields as $field) {
    			$newPromo[$field] = $promo[$field];
    			unset($promo[$field]);
    		}
    		
    		if ($promo['idPub'] == null) {
    		    $promo['itsOn'] = 'zero';
    		}
    		
    		$promo['itsOn'] = $newPromo['itsOn'] = ($promo['itsOn'] ?: 'none');
			
    		if (isset($return[$promo['id']])) {
    			$return[$promo['id']]['promos'][] = $newPromo;
    		} else {
    			$return[$promo['id']] = $promo;
    			$return[$promo['id']]['promos'] = array($newPromo);
    		}
    		
    	}
    	
    	return $return;
    }

    public function findPubByLatLong($latitude, $longitude)
    {
        $select = $this->_getFindPubSelect($latitude, $longitude);
        return $select->getTable()->fetchAll($select);
    }
    
    
    public function getCheckedProgressByState(array $states) {
        
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        
        $select = new Zend_Db_Select($db);
        
        $select
            ->from(array('p' => 'pub'), array())
            ->join(array('a' => 'address'), 'p.idAddress = a.id', array('state','count' => new Zend_Db_Expr('count(1)')))
            ->where('state in (?)', $states)
            ->group('state')
        ;
        
        $rows0 = $db->fetchPairs($select);
        
        $select
            ->where('p.isChecked = 1')
        ;
        
        $rows1 = $db->fetchPairs($select);
        
        $memory = array();
        
        foreach ($rows0 as $key => $value) {
            $memory[$key] = array(idx($rows1, $key, 0), $value);
        }
        
        return $memory;
    }
}
