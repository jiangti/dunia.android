<?php
class Model_DbTable_Row_Pub extends Model_DbTable_Row_RowAbstract {
    protected $_address;

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

	/**
	 * @return Model_DbTable_Row_Address|null
	 */
	public function getAddress() {
	    if ($this->_address) {
	        return $this->_address;
	    } else {
	        return $this->findParentRow('Model_DbTable_Address');
	    }
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
}