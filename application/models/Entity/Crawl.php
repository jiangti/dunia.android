<?php

/**
 * @Entity
 * @Table(name="crawl")
 */
class Dol_Model_Entity_Crawl extends Dol_Model_Entity
{
    
    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
	 * @OneToOne(targetEntity="Dol_Model_Entity_User")
	 * @JoinColumn(name="idUserSubmitted", referencedColumnName="id")
	 */
	protected $idUserSubmitted;

	/**
     * @OneToMany(targetEntity="Dol_Model_Entity_CrawlHasVenue", mappedBy="crawl")
     */
    protected $stops;

    /**
     * @Column(name="name", type="string")
     */
    protected $name;

	/**
     * @Column(name="description", type="text")
     */
    protected $description;
    
    /**
     * @Column(name="distance", type="decimal")
     */
    protected $distance;
	
}
