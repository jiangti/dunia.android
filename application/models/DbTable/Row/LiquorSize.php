<?php
class Model_DbTable_Row_LiquorSize extends Model_DbTable_Row_RowAbstract {
    public function __toString() {
        return $this->name;
    }
}