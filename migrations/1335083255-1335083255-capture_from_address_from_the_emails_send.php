<?php
/**
 * Capture from address from the emails send 
 */
class DbSchema_1335083255 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "ALTER TABLE `mailShare` ADD COLUMN `from` VARCHAR(255) NULL DEFAULT NULL  AFTER `attachment` ;";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}