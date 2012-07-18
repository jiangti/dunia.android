<?php
/**
 * CHecking will be useful for database bulding. 
 */
class DbSchema_1342605991 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "ALTER TABLE `pub` ADD COLUMN `isChecked` TINYINT(1) UNSIGNED NULL AFTER `checkinsCount`;";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
