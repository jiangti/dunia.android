<?php
class Service_Pub extends Aw_Service_ServiceAbstract
{

    protected $_promoFields = array('timeStart', 'timeEnd', 'price', 'liquorType', 'liquorSize');

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
     * @param unknown_type $latitude
     * @param unknown_type $longitude
     * @return Zend_Db_Table_Select
     */
    protected function _getFindPubSelect($latitude, $longitude, $query = null) {
    	$pubTable = new Model_DbTable_Pub();
        
        $select = $pubTable->select()
            ->setIntegrityCheck(false)
            ->from(array('p' => 'pub'))
            ->join(array('a' => 'address'),
                    'p.idAddress = a.id',
                    array(
                        'longitude',
                        'latitude',
                        'distance' => new Zend_Db_Expr("ROUND(6371000 * acos(cos(radians('$latitude')) * cos(radians(latitude)) * cos(radians(longitude) - radians('$longitude')) + sin(radians('$latitude')) * sin(radians(latitude))), 2)"))
                    )
            ->order('distance')
        ;
        
        if ($query) {
        	$select
        		->where('p.name like ?', sprintf('%s%%', $query))
        		->orWhere(sprintf('a.address1 like "%%%s%%" or a.postcode = "%s" or a.town like "%%%s%%"', $query, $query, $query))
        	;
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
    public function findPromo($latitude, $longitude, $query = null, $dayOfWeek = null, $hour = null) {
    	$select = $this->_getFindPubSelect($latitude, $longitude, $query);
    	
    	if ($hour) {
    		$hour = str_pad($hour . ':00:00', 8, '0', STR_PAD_LEFT);
    	} else {
	    	$hour = date("H:00:00");
    	}
    	
    	if (!$dayOfWeek) {
    		$dayOfWeek = date('D');
    	}
    	
    	if (time() > strtotime('11am')) {
	    	$expr = new Zend_Db_Expr(sprintf("CASE
						WHEN '%s' BETWEEN p0.timeStart AND p0.timeEnd THEN 'now'
						WHEN '%s' < p0.timeStart THEN 'later'
						ELSE 'earlier'
						END", $hour, $hour));
    		
    	} else {
    		$expr = new Zend_Db_Expr('"later"');
    	}
    	
    	
    	$select
    		->join(array('php' => 'pubHasPromo'), 'php.idPub = p.id', array())
    		->join(array('p0' => 'promo'), 'p0.id = php.idPromo', array(
                                                                       'timeStart' => 'DATE_FORMAT(timeStart, "%H:%i")',
                                                                       'timeEnd' => 'DATE_FORMAT(timeEnd, "%H:%i")',
                                                                       'price', 'itsOn' => $expr))
            ->join(array('phl' => 'promoHasLiquorType'), 'p0.id = phl.idPromo', array())
            ->join(array('lt' => 'liquorType'), 'lt.id = phl.idLiquorType', array('liquorType' => 'name'))
            ->joinLeft(array('ls' => 'liquorSize'), 'phl.idLiquorSize = ls.id', array('liquorSize' => 'name'))
    		->where('find_in_set(?, p0.day)', $dayOfWeek)
    		//->group('p.id')
    	;

        $data   = $select->getTable()->fetchAll($select);
        $return = array();

        foreach ($data as $promo) {
            $newPromo = array();
            $address  = (string) $promo->getAddress();

            $promo = $promo->toArray();
            $promo['address'] = $address;

            foreach ($this->_promoFields as $field) {
                $newPromo[$field] = $promo[$field];
                unset($promo[$field]);
            }

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
