<?php

/**
 * @Entity
 * @Table(name="telephone")
 */
class Dol_Model_Entity_Telephone extends Dol_Model_Entity
{
	const TELEPHONE_TYPE_MOBILE = 1;
	const TELEPHONE_TYPE_HOME   = 2;
	const TELEPHONE_TYPE_WORK   = 3;
	const TELEPHONE_TYPE_FAX    = 4;

    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Column(name="number", type="string")
     */
    protected $number;
    
	/**
     * @Column(name="type", type="smallint")
     */
    protected $type;

}
