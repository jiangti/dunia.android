<?php

/**
 * @Entity
 * @Table(name="crawlHasVenue")
 */
class Dol_Model_Entity_CrawlHasVenue extends Dol_Model_Entity
{
    
    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
	/**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Crawl", inversedBy="stops")
     * @JoinColumn(name="idCrawl", referencedColumnName="id")
     */
    protected $crawl;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Venue", inversedBy="crawls")
     * @JoinColumn(name="idVenue", referencedColumnName="id")
     */
    protected $venue;
    
    /**
     * @Column(name="visitingOrder", type="integer")
     */
    protected $order;
	
}
