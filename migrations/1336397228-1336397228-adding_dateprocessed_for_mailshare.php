<?php
/**
 * Adding dateProcessed for MailShare 
 */
class DbSchema_1336397228 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "ALTER TABLE `mailShare` ADD COLUMN `dateProcessed` DATETIME NULL DEFAULT NULL  AFTER `from` ";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
