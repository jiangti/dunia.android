<?php
class Model_DbTable_Log extends Model_DbTable_TableAbstract {
	protected $_name = 'log';
	
    protected $_rowClass = 'Model_DbTable_Row_Log';
    
    protected $_referenceMap    = array(
        'pub' => array(
            'columns'           => 'idPub',
            'refTableClass'     => 'Model_DbTable_Pub',
            'refColumns'        => 'id'
        ),
    );
}