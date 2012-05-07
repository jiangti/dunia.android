<?php
/**
 * Drop duplicate schema_version 
 */
class DbSchema_1336390007 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `schema_version` ;";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
