<?php

/**
 * @Entity
 * @Table(name="deal")
 */
class Dol_Model_Entity_Deal extends Dol_Model_Entity
{

	public function __construct($values) {
		parent::__construct($values);
		
		$this->days 	 	 = new \Doctrine\Common\Collections\ArrayCollection();
		$this->types 	 	 = new \Doctrine\Common\Collections\ArrayCollection();
		$this->userFavorites = new \Doctrine\Common\Collections\ArrayCollection();
		$this->reviews 		 = new \Doctrine\Common\Collections\ArrayCollection();
		$this->flags		 = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	
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
     * @Column(name="timeStart", type="time", nullable="true")
     */
    protected $timeStart;
    
    	
    /**
     * @Column(name="timeEnd", type="time", nullable="true")
     */
    protected $timeEnd;
    		
    /**
     * @Column(name="dateStart", type="date", nullable="true")
     */
    protected $dateStart;
    	
    /**
     * @Column(name="dateEnd", type="date", nullable="true")
     */
    protected $dateEnd;
    
    /**
     * @Column(name="name", type="string")
     */
    protected $name;

	/**
     * @Column(name="description", type="text")
     */
    protected $description;
    
	
    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_DealHasDay", mappedBy="deal", cascade={"persist", "remove"})
     */
    protected $days;
    
	/**
     * Bidirectional - Many deals have Many types (OWNING SIDE)
     *
     * @ManyToMany(targetEntity="Dol_Model_Entity_DealType", inversedBy="deals")
     * @JoinTable(name="dealHasType",
     *   joinColumns={@JoinColumn(name="idDeal", referencedColumnName="id")},
     *   inverseJoinColumns={@JoinColumn(name="idDealType", referencedColumnName="id")}
     * )
     */
    protected $types;
    
    /**
     * Bidirectional - Many deals are favorited by many users (INVERSE SIDE)
     *
     * @ManyToMany(targetEntity="Dol_Model_Entity_User", mappedBy="favoriteDeals")
     */
    protected $userFavorites;
    
    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_DealReview", mappedBy="deal")
     */
    protected $reviews;
    
    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_DealFlagged", mappedBy="deal")
     */
    protected $flags;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Venue", inversedBy="deals")
     * @JoinColumn(name="idVenue", referencedColumnName="id")
     */
    protected $venue;
    
	/**
	 * Adds a user to the users favorited list
     * 
     * @param Dol_Model_Entity_User $user
	 */
	public function addUserFavorite($user) {
		$this->userFavorites->add($user);
	}
	
	/**
	 * 
	 * Unlinks the deal to any days that it was linked
	 */
	public function clearDays() {
		if ($this->id) {
			Zend_Registry::get('EntityManager')->createQuery('DELETE FROM Dol_Model_Entity_DealHasDay d where d.deal = ' . $this->id)->execute();
		}
	}
	
}
