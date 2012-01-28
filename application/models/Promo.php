<?php
class Model_Promo extends Aw_Model_ModelAbstract {
    
    public $id;
    public $idPub;
	public $timeStart;
	public $timeEnd;
	public $dateStart;
	public $dateEnd;
	public $day;
	public $price;
	public $idLiquorType;
	public $description;

	protected static $days = array(
	    'SUN' => 'Sun',
	    'MON' => 'Mon',
    	'TUE' => 'Tue',
    	'WED' => 'Wed',
    	'THU' => 'Thu',
    	'FRI' => 'Fri',
    	'SAT' => 'Sat'
	);
	
	public static function getDaysList() {
	    return self::$days;
	}
	
	public function getById($id) {
	    $promo = Model_DbTable_Promo::retrieveById($id);
	     
	    $this->setFromArray($promo);
	    
	    if ($this->timeStart) {
	        $this->timeStart = $this->parseTime($this->timeStart);
	    }
	    
	    if ($this->timeEnd) {
	        $this->timeEnd = $this->parseTime($this->timeEnd);
	    }
	    
	    return $this;
	}
	
	public function save() {
	    $promoTable = new Model_DbTable_Promo();

	    if ($this->id) {
	        $promoRow = $promoTable->find($this->id)->current();
	        $promoRow->setFromArray($this->getArray());
	    } else {
	        $promoRow = $promoTable->createRow($this->getArray());
	    }
	    
	    $promoRow->save();
	}
	
	protected function parseTime($mysqlTime) {
	    $timeArray = explode(':', $mysqlTime);
	    $dateTime  = new DateTime();
	    $dateTime->setTime($timeArray[0], $timeArray[1]);

	    return $dateTime;
	}
}