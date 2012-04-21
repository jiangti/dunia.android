<?php
/**
 * Email sharing database for intermediate processing of email shares 
 */
class DbSchema_1334992509 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `mailShare` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `subject` varchar(255) DEFAULT NULL,		
		  `body` text,
		  `attachment` text,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
