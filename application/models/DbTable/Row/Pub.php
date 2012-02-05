<?php
class Model_DbTable_Row_Pub extends Model_DbTable_Row_RowAbstract {
    protected $_address;
    
    protected $_referenceMap    = array(
        'address' => array(
        	'columns'       => 'idAddress',
        	'refTableClass' => 'Model_DbTable_Address',
        	'refColumns'    => 'id'
        ),
    );

	public function setAddress(Model_DbTable_Row_Address $address) {
	    // This should copy over and reuse the existing record.
	    if ($address1 = $this->findParentRow(new Model_DbTable_Address())) {
	        $addressData = $address->toArray();
	        unset($addressData['id']);
	        $address1->setFromArray($addressData);
	        $address = $address1;
	    }

	    $this->_address = $address;
	}
	
	public function getPromos() {
		return $this->findManyToManyRowset(new Model_DbTable_Promo(), new Model_DbTable_PubHasPromo());
	}
	
	public function addDealFromArray($data) {
		
	    $days = array();
	    foreach ($data['days'] as $day) {
	    	$dayEnum = new Model_Day($day);
	    	$days[] = $dayEnum->getAbbr();
	    }
	    
		$array['timeStart']   = $data['start'];
		$array['timeEnd']     = $data['end'];
		$array['day']         = $days;
		$array['price']       = $data['value'];
		
		$promo = Model_DbTable_Promo::getRow($array);
		$promo->save();
		
		foreach ($data['liquorType'] as $liquorType) {
			$promo->addLiquorTypeById($liquorType);
		}
		
		$this->addPromo($promo);
	}
	
	public function resetPromo() {
		foreach ($this->getPromos() as $promo) {
			$promo->delete();
		}
	}
	
	public function addPromo(Model_DbTable_Row_Promo $promo) {
		$data['idPub'] = $this->id;
		$data['idPromo'] = $promo->id;
		
		$row = Model_DbTable_PubHasPromo::getRow($data);
		$row->save();
		
		return $row;
	}

	/**
	 * Explicit optimization this takes n+1 problem away. Not suitable for very large dataset.
	 * @return Model_DbTable_Row_Address|null
	 */
	public function getAddress() {
		//return $this->findParentRow(new Model_DbTable_Address());
		if ($this->_address) { } else {
			foreach ($this->_rowset->getLoadRows(new Model_DbTable_Address(), 'address') as $row) {
				if ($row->id == $this->idAddress) {
					$this->_address = $row;
					break;
				}
			}
	    }
	    return $this->_address;
	}
	
	public function _save() {
	    parent::_save();
	    if ($this->_address) {
	        if (!$this->_address->id) {
	            $this->_address->save();
	        }

	        if ($this->_address->id != $this->idAddress) {
	            $this->idAddress = $this->_address->id;
	            $this->save();
	        }
	    }
	}
	
	public function __toString() {
		return $this->name;
	}
}