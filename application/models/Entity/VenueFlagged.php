<?php

/**
 * @Entity
 * @Table(name="venueFlagged")
 */
class Dol_Model_Entity_VenueFlagged extends Dol_Model_Entity
{

    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_User", inversedBy="venuesFlagged")
     * @JoinColumn(name="idUser", referencedColumnName="id")
     */
    protected $idUser;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Venue", inversedBy="flags")
     * @JoinColumn(name="idVenue", referencedColumnName="id")
     */
    protected $venue;
    
    /**
     * @Column(name="comment", type="text")
     */
    protected $comment;

}
