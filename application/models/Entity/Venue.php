<?php

/**
 * @Entity
 * @Table(name="venue")
 */
class Dol_Model_Entity_Venue extends Dol_Model_Entity
{

	public function __construct($values) {
		parent::__construct($values);
		
		$this->deals 	 	 = new \Doctrine\Common\Collections\ArrayCollection();
		$this->telephones 	 = new \Doctrine\Common\Collections\ArrayCollection();
		$this->userFavorites = new \Doctrine\Common\Collections\ArrayCollection();
		$this->reviews 		 = new \Doctrine\Common\Collections\ArrayCollection();
		$this->crawls 		 = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @OneToMany(targetEntity="Dol_Model_Entity_Deal", mappedBy="venue", cascade={"persist", "remove"})
     */
    protected $deals;

	/**
     * @ManyToMany(targetEntity="Dol_Model_Entity_Telephone")
     * @JoinTable(name="venueHasTelephone",
     *      joinColumns={@JoinColumn(name="idVenue", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="idTelephone", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $telephones;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Address")
     * @JoinColumn(name="idAddress", referencedColumnName="id")
     */
    protected $address;

    /**
     * Bidirectional - Many venues are favorited by many users (INVERSE SIDE)
     *
     * @ManyToMany(targetEntity="Dol_Model_Entity_User", mappedBy="favorites")
     */
    protected $userFavorites;
    
    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_VenueReview", mappedBy="venue")
     */
    protected $reviews;
    
    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_CrawlHasVenue", mappedBy="venue")
     */
    protected $crawls;
    
    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_VenueFlagged", mappedBy="venue")
     */
    protected $flags;
    
	/**
     * @Column(name="name", type="string")
     */
    protected $name;
    
	/**
     * @Column(name="description", type="text")
     */
    protected $description;
    
    /**
     * @Column(name="verified", type="boolean", nullable="true")
     */
    protected $verified;
    
	public function addDeal($deal) {
		$this->deals->add($deal);
	}

	/**
	 * Adds a user to the users favorited list
     * 
     * @param Dol_Model_Entity_User $user
	 */
	public function addUserFavorite($user) {
		$this->userFavorites->add($user);
	}
	
	
}
