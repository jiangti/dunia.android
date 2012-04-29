<?php
require_once APPLICATION_ROOT . '/library/Aw/Contrib/Fb/src/facebook.php';
class Aw_Service_Fb extends Facebook {
    /**
     * @return Dv_Table_Row_User
     */
    public function getUserRow() {
        $table = new Dv_Table_User();
        $select = $table->getSelect();
        $select->where('facebook = ?', $this->getUser());
        return $table->fetchRow($select);
    }
}