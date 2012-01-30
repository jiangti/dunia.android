<?php
class Service_Pub {
	public function savePub(Model_DbTable_Row_Pub $pub) {

	    $notEmpty = new Zend_Validate_NotEmpty();
	    if (!$notEmpty->isValid($pub->name)) {
	        throw new DomainException($notEmpty->getErrors());
	    }

	    $table = new Model_DbTable_Pub();

	    if (!$pub1 = $table->findByName($pub->name)) {
	    } else {
	        $pub = $pub1;
	    }


	    $db = $pub->getTable()->getAdapter();
	    try {
    	    $db->beginTransaction();
            $pub->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
        }


	}
	
	/**
	 * @param unknown_type $data
	 * array(
	 * 		location
	 * 		file0
	 * 		file1
	 * 		file2
	 * 		detail0 =>
	 * 			value
	 * 			start
	 *			end
	 *			liquorType = array()
	 *			days = array()
	 *
	 * );
	 */
	public function savePubFromShareArray($data) {
		$db = Model_Db::getInstance();
		
		try {
			
			$db->beginTransaction();
			
			
			$pub = Model_DbTable_Pub::getRow($data);
			
			$address = Model_DbTable_Address::createFromString($data['location']);
			$pub->setAddress($address);
			
			foreach ($data as $index => $value) {
				if (stripos($index, 'detail') !== false) {
				}
			}
			
			$pub->addDeal();
			
			$pub->save();
			
			exit('++');
			
		} catch (Exception $e) {
			
			$db->rollback();
			
			throw $e;
		}
		
		
		
		
	}
}