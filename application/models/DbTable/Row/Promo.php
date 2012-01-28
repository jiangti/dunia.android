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
}