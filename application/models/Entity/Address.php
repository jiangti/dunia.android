<?php

/**
 * @Entity
 * @Table(name="address")
 */
class Dol_Model_Entity_Address extends Dol_Model_Entity
{
    
    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(name="address1", type="string")
     */
    protected $address1;
    
    /**
     * @Column(name="address2", type="string")
     */
    protected $address2;
    
    /**
     * @Column(name="city", type="string")
     */
    protected $city;
    
    /**
     * @Column(name="state", type="string")
     */
    protected $state;
    
    /**
     * @Column(name="postCode", type="string")
     */
    protected $postCode;
    
    /**
     * @Column(name="country", type="string")
     */
    protected $country;
    
    /**
     * @Column(name="latitude", type="string", nullable="true")
     */
    protected $latitude;
    
    /**
     * @Column(name="longitude", type="string", nullable="true")
     */
    protected $longitude;

    public function format() {
    	$string = '';
    	if($this->address1)
    		$string .= '<div>' . $this->address1 . '</div>';
    	if($this->address2)
    		$string .= '<div>' . $this->address2 . '</div>';
    	$string .= '<div>' . implode(' ', array($this->city, $this->state, $this->postCode)) . '</div>';
    	if($this->country)
    		$string .= '<div>' . $this->country . '</div>';
    	return $string;
    }
    
    public function formatLine() {
    	 return trim(preg_replace('/\s+/', ' ', implode(' ', array($this->address1, $this->address2, $this->city, $this->state, $this->postCode, $this->country))));
    }
}
