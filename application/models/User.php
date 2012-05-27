<?php
class Model_User extends Aw_Model_ModelAbstract {

    public $id;
    public $firstName;
    public $login;
    public $password;
    public $lastName;
    public $email;
    public $birthDate;



	
	const DEFAULT_LATITUDE  = -33.8757;
	const DEFAULT_LONGITUDE = 151.206;

	public static function getInstance() {
		return new self();
	}
	
	
	public function getLat() {
		return self::DEFAULT_LATITUDE;
	}
	
	public function getLong() {
		return self::DEFAULT_LONGITUDE;
	}

    public function save() {
        $userTable = new Model_DbTable_User();

        if ($this->id) {
            $userRow = $userTable->find($this->id)->current();
            $userRow->setFromArray($this->getArray());
        } else {
            $userRow = $userTable->createRow($this->getArray());
        }

        $userRow->save();
    }
}