<?php
/**
 * Adding 2 columns
 */
class DbSchema_1336643429 extends Akrabat_Db_Schema_AbstractChange
{
    public function up()
    {
        $sql = "ALTER TABLE `pub` ADD COLUMN `twitter` VARCHAR(128) NULL DEFAULT NULL  AFTER `validated` , ADD COLUMN `telephone` VARCHAR(45) NULL DEFAULT NULL  AFTER `twitter` ;";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}