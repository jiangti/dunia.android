<?php
class Model_DbTable_Row_Promo extends Model_DbTable_Row_RowAbstract {
    protected function _save() {
        
        if ($this->timeStart instanceof DateTime) {
            $this->timeStart = $this->timeStart->format('H:i');
        }
        
        if ($this->timeEnd instanceof DateTime) {
            $this->timeEnd = $this->timeEnd->format('H:i');
        }
        /**
         * Do some repair magic.
         */
        if (!is_array($this->day)) {
        	$this->day = str_replace(Model_Day::$daysName, Model_Day::$days, $this->day);
        	$this->day = explode(",", $this->day);
        }
        
        if ($this->day) {
            $this->day = implode(',', $this->day);
        }
        
        parent::_save();
    }
    
    public function addLiquorTypeById($liquorTypeId, $liquorSizeId = null) {
    	return $this->addLiquorType(Model_DbTable_LiquorType::retrieveById($liquorTypeId), Model_DbTable_LiquorSize::retrieveById($liquorSizeId));
    }
    
    public function findExists() {
    	$pub = $this->findPub();
    	$rows = $pub->findManyToManyRowset(new Model_DbTable_Promo(), new Model_DbTable_PubHasPromo());
    }
    
    public function addLiquorType(Model_DbTable_Row_LiquorType $liquorType, Model_DbTable_Row_LiquorSize $liquorSize = null) {
    	$data['idLiquorType'] = $liquorType->id;
    	$data['idPromo'] = $this->id;
    	
    	if ($liquorSize && $liquorSize->isPersist()) {
    		$data['idLiquorSize'] = $liquorSize->id;
    	}
    	
    	$row = Model_DbTable_PromoHasLiquorType::getRow($data);
    	try{
    	$row->save();
    	} catch (Exception $e) {
    	    echo $e->getMessage();
    	    var_dump($row->toArray()); exit;
    	}
    	return $row;
    }
    
    /**
     * @return Model_DbTable_Row_Pub
     */
    public function findPub() {
    	$pubTable = new Model_DbTable_Pub();
    	$select = $pubTable->select(true);
    	
    	$select
    		->setIntegrityCheck(false)
    		->join(array('php' => 'pubHasPromo'), 'php.idPub = pub.id', array())
    		->join(array('p' => 'promo'), 'p.id = php.idPromo', array())
    	;
    	
    	return $pubTable->fetchRow($select);
    }
    
    public function getLiquorSizes() {
    	/**
    	 * This should be optimized with static storing.
    	 */
    	return $this->findManyToManyRowset(new Model_DbTable_LiquorSize(), new Model_DbTable_PromoHasLiquorType());
    }
    
    public function getLiquorTypes() {
    	/**
    	 * This should be optimized with static storing.
    	 */
    	return $this->findManyToManyRowset(new Model_DbTable_LiquorType(), new Model_DbTable_PromoHasLiquorType());
    }
    
    public function setStartTime($start, $minute = 0) {
    	$date = new DateTime();
    	$date->setTime($start, $minute);
    	$this->timeStart = $date;
    	return $this;
    }
    
    public function setEndTime($end, $minute = 0) {
    	$date = new DateTime();
    	$date->setTime($end, $minute);
    	$this->timeEnd = $date;
    	return $this;
    }
    
    public function parseTime($str) {
    	if (stripos($str, "all day") !== false) {
    		$this->setStartTime(0)->setEndTime(12);
    	} else {
	    	$str = str_replace(" to ", "-", $str);
	    	$str = preg_replace('/[^0-9-,\.]/', '', $str);
	    	$times = explode('-', $str);
	    	$this->setStartTime($times[0]);
	    	$this->setEndTime($times[1]);
    	}
    }
}