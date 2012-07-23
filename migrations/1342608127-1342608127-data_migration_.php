<?php
/**
 * Data migration.
 */
class DbSchema_1342608127 extends Akrabat_Db_Schema_AbstractChange
{
    public function up()
    {
        $sqls = "UPDATE
            		pub p
            	SET isChecked = (SELECT COUNT(1) FROM pubHasPromo php WHERE php.idPub = p.id);

                UPDATE
                    pub p
                SET isChecked = NULL WHERE isChecked = 0";
        $this->_executeMultiLine($sqls);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
