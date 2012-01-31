<?php
class Model_DbTable_PromoHasLiquorType extends Aw_Table_Relation_TableAbstract {
	protected $_name = 'promoHasLiquorType';
	protected $_rowClass = 'Model_DbTable_Row_PromoHasLiquorType';
	protected $_primary = array('idPromo', 'idLiquorType');
	
	protected $_referenceMap = array(
		'promo' => array(
			'columns'       => 'idPromo',
			'refTableClass' => 'Model_DbTable_Promo',
			'refColumns'    => 'id'
		),
		'liquorType' => array(
			'columns'       => 'idLiquorType',
			'refTableClass' => 'Model_DbTable_LiquorType',
			'refColumns'    => 'id'
		)
	);
	
}