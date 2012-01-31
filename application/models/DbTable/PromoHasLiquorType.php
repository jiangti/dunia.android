<?php
class Model_DbTable_PromoHasLiquorType extends Aw_Table_Relation_TableAbstract {
	protected $_name = 'promoHasLiquorType';
	protected $_rowClass = 'Model_DbTable_Row_PromoHasLiquorType';
	protected $_primary = array('idPromo', 'idLiquorType');
	
}