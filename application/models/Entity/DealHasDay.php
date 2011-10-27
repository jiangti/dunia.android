<?php

/**
 * @Entity
 * @Table(name="dealHasDay")
 */
class Dol_Model_Entity_DealHasDay extends Dol_Model_Entity
{
    
	const DAY_SUNDAY    = 0;
	const DAY_MONDAY    = 1;
	const DAY_TUESDAY   = 2;
	const DAY_WEDNESDAY = 3;
	const DAY_THURSDAY  = 4;
	const DAY_FRIDAY    = 5;
	const DAY_SATURDAY  = 6;
	
	public $daysShort = array(
		'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
	);
	
	public $daysLong = array(
		'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
	);
	
    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id; 
    
	/**
     * @Column(name="day", type="integer")
     */
    protected $day;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Deal", inversedBy="days")
     * @JoinColumn(name="idDeal", referencedColumnName="id")
     */
    protected $deal;
    
    public function getDayWording($long = false) {
    	if ($long) {
    		return $this->daysLong[$this->day];
    	}
    	return $this->daysShort[$this->day];
    }
}
