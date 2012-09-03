<?php
/**
 * implementing pub like 
 */
class DbSchema_1346675874 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "CREATE TABLE userLikesPub (
                  idPub int(11) unsigned NOT NULL,
                  idUser int(11) unsigned NOT NULL,
                  PRIMARY KEY (idPub,idUser),
                  KEY user (idUser),
                  CONSTRAINT user FOREIGN KEY (idUser) REFERENCES user (id),
                  CONSTRAINT pub FOREIGN KEY (idPub) REFERENCES pub (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}