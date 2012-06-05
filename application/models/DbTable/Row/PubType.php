<?php
class Model_DbTable_Row_PubType extends Model_DbTable_Row_RowAbstract {
    public function isExists() {
        return (boolean) count($this->getTable()->find($this->id));
    }
}