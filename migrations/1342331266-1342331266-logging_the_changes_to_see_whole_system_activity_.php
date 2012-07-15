<?php
/**
 * Logging the changes to see whole system activity.
 */
class DbSchema_1342331266 extends Akrabat_Db_Schema_AbstractChange
{
    public function up()
    {
        $sqls = "
            CREATE TABLE `log`(
              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `message` VARCHAR(255),
              `datetime` TIMESTAMP(0),
              `priority` INT UNSIGNED,
              `idPub` INT UNSIGNED NULL,
              PRIMARY KEY (`id`)
            );
            
            ALTER TABLE `log`
              ADD CONSTRAINT `fk_log_pub1` FOREIGN KEY (`idPub`) REFERENCES `pub`(`id`) ON UPDATE CASCADE ON DELETE CASCADE;";
        
        $this->_executeMultiLine($sqls);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
