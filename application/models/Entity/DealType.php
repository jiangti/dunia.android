<?php

/**
 * @Entity
 * @Table(name="dealType")
 */
class Dol_Model_Entity_DealType extends Dol_Model_Entity
{

    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Column(name="name", type="string")
     */
    protected $name;
    
	/**
     * @Column(name="picture", type="string")
     */
    protected $picture;
    
    /**
     * @ManyToMany(targetEntity="Dol_Model_Entity_Deal", mappedBy="$types")
	 */
    protected $deals;

}
