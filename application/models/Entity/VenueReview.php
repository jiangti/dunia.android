<?php

/**
 * @Entity
 * @Table(name="venueReview")
 */
class Dol_Model_Entity_VenueReview extends Dol_Model_Entity
{

    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
     * @Column(name="description", type="text")
     */
    protected $description;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_User", inversedBy="venueReviews")
     * @JoinColumn(name="idUser", referencedColumnName="id")
     */
    protected $author;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Venue", inversedBy="reviews")
     * @JoinColumn(name="idVenue", referencedColumnName="id")
     */
    protected $venue;

	

}
