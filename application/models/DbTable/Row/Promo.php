<?php
class Model_DbTable_Row_Promo extends Model_DbTable_Row_RowAbstract {
    protected function _save() {
        
        if ($this->timeStart instanceof DateTime) {
            $this->timeStart = $this->timeStart->format('H:i');
        }
        
        if ($this->timeEnd instanceof DateTime) {
            $this->timeEnd = $this->timeEnd->format('H:i');
        }
        
        if ($this->day) {
            $this->day = implode(',', $this->day);
        }
        parent::_save();
    }
    
    public function addLiquorTypeById($liquorTypeId) {
    	return $this->addLiquorType(Model_DbTable_LiquorType::retrieveById($liquorTypeId));
    }
    
    public function addLiquorType(Model_DbTable_Row_LiquorType $liquorType) {
    	$data['idLiquorType'] = $liquorType->id;
    	$data['idPromo'] = $this->id;
    	$row = Model_DbTable_PromoHasLiquorType::getRow($data);
    	$row->save();
    	return $row;
    }
    
    public function getLiquorTypes() {
    	return $this->findManyToManyRowset(new Model_DbTable_LiquorType(), new Model_DbTable_PromoHasLiquorType());
    }
}