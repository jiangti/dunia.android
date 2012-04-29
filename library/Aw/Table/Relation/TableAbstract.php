<?php
/**
 * Special class for relationship tables with 2 keys that makes up the primary key.
 *
 * @author jiangti
 */
abstract class Aw_Table_Relation_TableAbstract extends Aw_Table_TableAbstract {
	
	/**
	 * @return Aw_Table_Row
	 */
	public static function getRow(array $data = array()) {
		$table = new static();
		
		$primaryKeys = $table->info('primary');
		
		foreach ($primaryKeys as $name) {
			$default[$name] = null;
			$key[] = $name;
		}
	
		$data += $default;
	
		
		
		$rows = $table->find($data[$key[0]], $data[$key[1]]);
		if ($rows->count() == 1) {
			return $rows->current();
		} else {
			return parent::getRow($data);
		}
	}
}