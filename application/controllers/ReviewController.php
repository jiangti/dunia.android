<?php

class ReviewController extends Dol_Controller
{
    public function init() {
    }

    public function indexAction() {
    }

    public function venueAction() {
        $id    = $this->_getParam('id');
        $venue = $this->_em->find('Dol_Model_Entity_Venue', $id);
        $form  = $this->_prepareForm();

        if($this->_request->isPost()) {
            $post = $this->_request->getPost();
            $post['author'] = $this->_em->find('Dol_Model_Entity_User', Zend_Auth::getInstance()->getIdentity()->id);
            $post['venue']  = $venue;
            $review = new Dol_Model_Entity_VenueReview($post);
            $this->_em->persist($review);
            $this->_em->flush();

	        if($this->isAjax()) {
				exit;
			} else {
            	$this->_redirect(sprintf('/venue/index/idVenue/%s',$venue->id));
			}
        }
		
        $this->view->form  = $form;
        $this->view->venue = $venue;
    }

    public function dealAction() {
    	
        $id   = $this->_getParam('id');
        $deal = $this->_em->find('Dol_Model_Entity_Deal', $id);
        $form = $this->_prepareForm();

        if($this->_request->isPost()) {
            $post = $this->_request->getPost();
            $post['author'] = $this->_em->find('Dol_Model_Entity_User', Zend_Auth::getInstance()->getIdentity()->id);
            $post['deal'] 	= $deal;
            $review = new Dol_Model_Entity_DealReview($post);
            $this->_em->persist($review);
            $this->_em->flush();

        	if($this->isAjax()) {
				exit;
			} else {
            	$this->_redirect(sprintf('/venue/index/idVenue/%s',$venue->id));
			}
        }

        $this->view->form = $form;
        $this->view->deal = $deal;
    }
    
    public function deleteVenueAction() {
    	
        $idReview = $this->_getParam('idReview');
        $idVenue = $this->_getParam('id');
        $review = $this->_em->find('Dol_Model_Entity_VenueReview', $idReview);

        $this->_em->remove($review);
        $this->_em->flush();

        $this->_redirect(sprintf('/venue/index/idVenue/%s', $idVenue));
        
    }    


    public function deleteDealAction()
    {
        $idReview = $this->_getParam('idReview');
        $idDeal = $this->_getParam('id');

        $review = $this->_em->find('Dol_Model_Entity_DealReview', $idReview);
        $deal = $review->deal;
        $idVenue = $deal->venue->id;

        $this->_em->remove($review);
        $this->_em->flush();

        $this->_redirect(sprintf('/venue/index/idVenue/%s', $idVenue));
    }    
    
    protected function _prepareForm() {
    	$options = array(
        	'action' => $_SERVER['REQUEST_URI']
        );
    	
        if ($this->isAjax()) {
			$options['class'] = 'ajaxForm';
		}
		
        return new Dol_Model_Forms_Review_Deal($options);
    }
}
