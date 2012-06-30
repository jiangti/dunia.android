<?php
/**
 * Wrong day arrangement 
 */
class DbSchema_1341045699 extends Akrabat_Db_Schema_AbstractChange 
{
    public function up()
    {
        $sql = "ALTER TABLE `promo` CHANGE `day` `day` SET('MON','TUE','WED','THU','FRI','SAT','SUN') CHARSET utf8 COLLATE utf8_general_ci NULL;
";
        $this->_db->query($sql);
    }
    
    public function down()
    {
        $this->_db->query($sql);
    }
}
