<?php
class Aw_Form_Element_Time extends Aw_Form_Element_Text {

	public $helper = 'formTime';

    protected $hour = null;
    protected $minutes = null;
    
    public function setHour($hour) {
        $this->hour = $hour;
        return $this;
    }

    public function setMinutes($minutes) {
        $this->minutes = $minutes;
        return $this;
    }

    public function setValue($value) {
    	if ($value instanceof DateTime) {
    		$this->setHour($value->format('H'))->setMinutes($value->format('i'));
    	} elseif (is_array($value) && isset($value['hour']) && isset($value['minutes'])) {
            $this->setHour($value['hour'])->setMinutes($value['minutes']);
        }
    }

    public function getValue() {
        if (!$this->hour || !$this->minutes)
            return false;
        $date = new DateTime();
        return $date->setTime($this->hour, $this->minutes);
    }
}