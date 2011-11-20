<?php
class Model_DbTable_Row_RowAbstract extends Aw_Table_Row {
    protected function _save() {}

    protected function _postSave() {}

    public function save() {
        $this->_save();
        $return = parent::save();
        $this->_postSave();
        return $return;
    }
}