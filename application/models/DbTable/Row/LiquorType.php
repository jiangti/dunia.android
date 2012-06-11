<?php
class Model_DbTable_Row_LiquorType extends Model_DbTable_Row_RowAbstract {
    public function __toString() {
        return $this->name;
    }
}