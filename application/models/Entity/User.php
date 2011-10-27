<?php

/**
 * @Entity
 * @Table(name="user")
 */
class Dol_Model_Entity_User extends Dol_Model_Entity
{

    const NOT_FOUND = 1;
    const WRONG_PW  = 2;

	public function __construct($values) {
		parent::__construct($values);
		
		$this->favoriteVenues = new \Doctrine\Common\Collections\ArrayCollection();
		$this->favoriteDeals  = new \Doctrine\Common\Collections\ArrayCollection();
		$this->venueReviews   = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dealReviews 	  = new \Doctrine\Common\Collections\ArrayCollection();
		$this->venuesFlagged  = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dealsFlagged	  = new \Doctrine\Common\Collections\ArrayCollection();
	}

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
	 * @Column(type="string", length=32, unique=true, nullable=false)
	 */
	protected $username;

	/**
	 * @Column(type="string", length=100, nullable=false)
	 */
	protected $password;

    /**
	 * @Column(type="string", length=128, unique=true, nullable=false)
	 */
	protected $emailAddress;

	/**
	 * @Column(type="string", length=128, nullable=false)
	 */
	protected $firstName;

	/**
	 * @Column(type="string", length=128, nullable=false)
	 */
	protected $lastName;

	/**
	 * @Column(type="boolean", nullable=false)
	 */
	protected $active;
	
	/**
	 * @Column(type="string", length=128, nullable=true)
	 */
	protected $code;
	
	/**
	 * @Column(type="datetime", nullable=false)
	 */
	protected $dateRegistered;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	protected $lastLogin;
	
	/**
     * Bidirectional - Many users have Many favorite venues (OWNING SIDE)
     *
     * @ManyToMany(targetEntity="Dol_Model_Entity_Venue", inversedBy="userFavorites", cascade={"persist"})
     * @JoinTable(name="userHasFavoriteVenue",
     *   joinColumns={@JoinColumn(name="idUser", referencedColumnName="id")},
     *   inverseJoinColumns={@JoinColumn(name="idVenue", referencedColumnName="id")}
     * )
     */
    protected $favoriteVenues;

    /**
     * Bidirectional - Many users have Many favorite deals (OWNING SIDE)
     *
     * @ManyToMany(targetEntity="Dol_Model_Entity_Deal", inversedBy="userFavorites")
     * @JoinTable(name="userHasFavoriteDeal",
     *   joinColumns={@JoinColumn(name="idUser", referencedColumnName="id")},
     *   inverseJoinColumns={@JoinColumn(name="idDeal", referencedColumnName="id")}
     * )
     */
    protected $favoriteDeals;

    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_VenueReview", mappedBy="author")
     */
    protected $venueReviews;

    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_DealReview", mappedBy="author")
     */
    protected $dealReviews;

    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_VenueFlagged", mappedBy="idUser")
     */
    protected $venuesFlagged;

    /**
     * @OneToMany(targetEntity="Dol_Model_Entity_DealFlagged", mappedBy="idUser")
     */
    protected $dealsFlagged;

    /**
     * Perform authentication of a user
     * @param string $username
     * @param string $password
     */
    public static function authenticate($username, $password, $entityManager)
    {
        $user = $entityManager->getRepository('Dol_Model_Entity_User')->findOneBy(array('username' => $username));

        if ($user)
        {
            if ($user->password == $password)
                return $user;

            throw new Exception(self::WRONG_PW);
        }
        throw new Exception(self::NOT_FOUND);
    }

    public static function exists($username, $entityManager) {
        $user = $entityManager->getRepository('Dol_Model_Entity_User')->findOneBy(array('username' => $username));
        if ($user)
            return true;
        return false;
    }

    public static function existsEmail($email, $entityManager) {
        $user = $entityManager->getRepository('Dol_Model_Entity_User')->findOneBy(array('emailAddress' => $email));
        if ($user)
            return true;
        return false;
    }
    
    public function formatName() {
    	return $this->firstName . ' ' . $this->lastName;
    }
    
    /**
     * Adds a venue to the user's favorites
     * 
     * @param Dol_Model_Entity_Venue $venue
     */
    public function favoriteVenue($venue) {
    	$venue->addUserFavorite($this);
    	if(!$this->favoriteVenues->contains($venue)) {
    		$this->favoriteVenues->add($venue);
    	}
    }
    
	/**
     * Removes a venue from the user's favorites
     * 
     * @param Dol_Model_Entity_Venue $venue
     */
    public function unfavoriteVenue($venue) {
    	//$venue->removeUserFavorite($this);
    	if($this->favoriteVenues->contains($venue)) {
    		$this->favoriteVenues->removeElement($venue);
    	}
    }
    
	/**
     * Adds a deal to the user's favorites
     * 
     * @param Dol_Model_Entity_Deal $deal
     */
    public function favoriteDeal($deal) {
    	$deal->addUserFavorite($this);
    	if(!$this->favoriteDeals->contains($deal)) {
    		$this->favoriteDeals->add($deal);
    	}
    }
    
}
