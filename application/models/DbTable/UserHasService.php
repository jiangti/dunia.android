<?php
class Model_DbTable_UserHasService extends Aw_Table_Relation_TableAbstract {
	protected $_name     = 'userHasService';
	protected $_primary  = array('idUser', 'idService');
    protected $_rowClass = 'Model_DbTable_Row_UserHasService';
	
	protected $_referenceMap = array(
		'user' => array(
			'columns'       => 'idUser',
			'refTableClass' => 'Model_DbTable_User',
			'refColumns'    => 'id'
		),
		'service' => array(
			'columns'       => 'idService',
			'refTableClass' => 'Model_DbTable_Service',
			'refColumns'    => 'id'
		)
	);
}