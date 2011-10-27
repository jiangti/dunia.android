<?php

/**
 * @Entity
 * @Table(name="dealFlagged")
 */
class Dol_Model_Entity_DealFlagged extends Dol_Model_Entity
{

    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_User", inversedBy="dealsFlagged")
     * @JoinColumn(name="idUser", referencedColumnName="id")
     */
    protected $idUser;
    
    /**
     * @ManyToOne(targetEntity="Dol_Model_Entity_Deal", inversedBy="flags")
     * @JoinColumn(name="idDeal", referencedColumnName="id")
     */
    protected $deal;
    
    /**
     * @Column(name="comment", type="text")
     */
    protected $comment;
}
