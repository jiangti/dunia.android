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
}