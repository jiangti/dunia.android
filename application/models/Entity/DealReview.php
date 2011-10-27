<?php

/**
 * @Entity
 * @Table(name="dealReview")
 */
class Dol_Model_Entity_DealReview extends Dol_Model_Entity
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
     * @ManyToOne(targetEntity="Dol_Model_Entity_User", inversedBy="dealReviews")
     * @JoinColumn(name="idUser", referencedColumnName="id")
     */
    protected $author;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Deal", inversedBy="reviews")
     * @JoinColumn(name="idDeal", referencedColumnName="id")
     */
    protected $deal;

}
