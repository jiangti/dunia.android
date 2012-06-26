<?php
/**
 * The idPub 
 */
class DbSchema_1340716315 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "ALTER TABLE `mailShare` ADD COLUMN `idPub` INT(10) UNSIGNED NULL DEFAULT NULL  AFTER `dateProcessed` , 
  ADD CONSTRAINT `fk_mailShare_pub1`
  FOREIGN KEY (`idPub` )
  REFERENCES `thirst_db`.`pub` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_mailShare_pub1` (`idPub` ASC) ;";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
