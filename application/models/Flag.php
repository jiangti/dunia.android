<?php
class Model_Flag extends Aw_Model_ModelAbstract {

    const FLAG_TYPE_CLOSED  = 1;
    const FLAG_TYPE_ADDRESS = 2;
    const FLAG_TYPE_PROMO   = 3;

    const FLAG_STATUS_NOT_PROCESSED = 0;
    const FLAG_STATUS_ACCEPTED      = 1;
    const FLAG_STATUS_REJECTED      = 2;

    public static $flagType = array(
        self::FLAG_TYPE_CLOSED  => 'Venue Closed',
        self::FLAG_TYPE_ADDRESS => 'Incorrect Address',
        self::FLAG_TYPE_PROMO   => 'Incorrect Promo'
    );

    public $id;
	/**
	 * @var Model_Pub
	 */
	public $pub;
    /**
     * @var Model_User
     */
	public $user;
	public $type;
	public $status;
	public $dateAdded;
    public $data;

    public $idPub;
    public $idUser;

	public function setPub(Model_Pub $pub) {
		$this->$pub = $pub;
	}

    public function setUser(Model_User $user) {
        $this->$user = $user;
    }
	
	public function save() {
	    $flagTable = new Model_DbTable_Flag();

	    if ($this->pub) {
	        $this->idPub = $this->pub->save();
	    }

        if ($this->user) {
            $this->idUser = $this->user->save();
        }
	    
	    if ($this->id) {
	        $flagRow = $flagTable->find($this->id)->current();
	        $flagRow->setFromArray($this->getArray());
	    } else {
	        $flagRow = $flagTable->createRow($this->getArray());
	    }
	    
	    $flagRow->save();
	}
}