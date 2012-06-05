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
                
                $pub->resetPromo();
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
            
            foreach ($data as $index => $value) {
                if (stripos($index, 'detail') !== false && ($value['value'] || $value['description'])) {
                    $pub->addDealFromArray($value);
                }
            }
            
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
    protected function _getPubSelect($latitude, $longitude, Model_Location_Bound $bound = null) {
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
	    	->from(array('p' => 'pub'))
	    	->join(array('a' => 'address'),
    			'p.idAddress = a.id',
    			array(
    				'longitude',
    				'latitude',
    				'distance' => new Zend_Db_Expr("ROUND(6371000 * acos(cos(radians('$latitude')) * cos(radians(a.latitude)) * cos(radians(a.longitude) - radians('$longitude')) + sin(radians('$latitude')) * sin(radians(a.latitude))), 2)"))
    			)
	    	->order('distance')
	    	->where(sprintf('a.latitude between %s and %s', $x0, $x1))
	    	->where(sprintf('a.longitude between %s and %s', $y0, $y1))
    	;
    	
    	return $select;
    }

    /**
     * @param unknown_type $latitude
     * @param unknown_type $longitude
     * @return Zend_Db_Table_Select
     */
    protected function _getFindPubSelect($latitude, $longitude, $query = null, Model_Location_Bound $bound = null) {
        $select = $this->_getPubSelect($latitude, $longitude, $bound);
        
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
    private function _getPubsPromoSelect($latitude, $longitude, $query, $dayOfWeek, $hour, Model_Location_Bound $bound = null, $includeNoPromos = false) {
    	$select = $this->_getFindPubSelect($latitude, $longitude, $query, $bound);

    	if ($hour) {
    		$hour = str_pad($hour . ':00:00', 8, '0', STR_PAD_LEFT);
    	} else {
    		$hour = date("H:i:00");
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
                END, 'none')", date('D'), $hour, $hour, $hour));

    	$select
	    	->$joinType(array('php' => 'pubHasPromo'), 'php.idPub = p.id', array('idPub'))
	    	->$joinType(array('p0' => 'promo'), 'p0.id = php.idPromo',
	    		array(
	    			'timeStart' => 'DATE_FORMAT(p0.timeStart, "%H:%i")',
	    			'timeEnd'   => 'DATE_FORMAT(p0.timeEnd, "%H:%i")', 'price', 'itsOn' => $expr)
	    		)
	    	->$joinType(array('phl' => 'promoHasLiquorType'), 'p0.id = phl.idPromo', array())
	    	->$joinType(array('lt' => 'liquorType'), 'lt.id = phl.idLiquorType', array('liquorType' => 'name'))
	    	->joinLeft(array('ls' => 'liquorSize'), 'phl.idLiquorSize = ls.id', array('liquorSize' => 'name'));

    	$select0 = clone $select;
    	
	    $select0->where('find_in_set(?, p0.day)', date('D'));
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $rows = $db->fetchAll($select0);
	    $id = array_unique(ipull($rows, 'id'));
	    
	    $select1 = clone $select;

        if ($includeNoPromos) {
            $select1->where('find_in_set(?, p0.day) = 0 OR p0.day is null', date('D'));
        } else {
            $select1->where('find_in_set(?, p0.day) = 0', date('D'));
        }

        if ($id) {
            $select1->where('p.id not in (?)', $id);
        }
	    $select->where('find_in_set(?, p0.day)', date('D'))->reset(Zend_Db_Select::ORDER);

	    $unionSelect = $select->getTable()->select(false);
	    return $unionSelect->union(array($select, $select1));
	    
	    
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
    public function findPromo($latitude, $longitude, $query = null, $dayOfWeek = null, $hour = null, Model_Location_Bound $bound = null, $includeNoPromos = false) {
    	
    	$table = new Model_DbTable_Pub();
    	
    	$select = $this->_getPubsPromoSelect($latitude, $longitude, $query, $dayOfWeek, $hour, $bound, $includeNoPromos);

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
}
