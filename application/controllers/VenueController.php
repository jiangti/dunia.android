<?php

class VenueController extends Dol_Controller
{

	public function init()
    {
        $this->view->windowTitle = 'DOL (Alpha...)';
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('css/general.css'));
    }

	public function postDispatch()
	{
		$this->_em->flush();
	}

    public function indexAction() {
		$venue = $this->_em->find('Dol_Model_Entity_Venue', $this->_getParam('idVenue'));
		$this->view->title   = $venue->name;
		$this->view->user    = $this->getAuthenticatedUser();		
		$this->view->venue   = $venue;
    }
    
	public function summaryAction() {
		$this->_helper->layout()->disableLayout();
		$venue = $this->_em->find('Dol_Model_Entity_Venue', $this->_getParam('idVenue'));
		$this->view->title   = $venue->name;
		$this->view->user    = $this->getAuthenticatedUser();		
		$this->view->venue   = $venue;
    }

	/**
	 * Adds a deal to a venue
	 *
	 * @return void
	 */
	public function newDealAction()
	{
		$form   = new Dol_Model_Forms_Deal();
		$params = $this->_getAllParams();

		if ($this->_request->isPost() && $form->isValid($params)) {
			
			$values = $form->getValues();
			
			if(isset($values['venue']) && is_numeric($values['venue'])) {
				$values['venue'] = $this->_em->find('Dol_Model_Entity_Venue', $values['venue']);
			}
			if(isset($values['id']) && $values['id']) {
				$deal = $this->_em->find('Dol_Model_Entity_Deal', $values['id']);
				foreach($values as $param => $val) {
					if($deal->hasAttribute($param) && gettype($deal->$param) == gettype($val)) {
						$deal->$param = $val;
					}
				}
			} else {
				$deal  = new Dol_Model_Entity_Deal($values);
			}
			
			$deal->clearDays();
			foreach ($values['days'] as $day) {
				$deal->days->add(new Dol_Model_Entity_DealHasDay(array('deal' => $deal, 'day' => $day)));
			}
			
			$deal->types = new \Doctrine\Common\Collections\ArrayCollection();
			foreach ($values['types'] as $type) {
				$deal->types->add($this->_em->find('Dol_Model_Entity_DealType', $type));
			}
			
			$this->_em->persist($deal);
			
			if($this->isAjax()) {
				$this->_em->flush();
				exit;
			}
			
			$this->_forward('index', null, null, array('idVenue' => $values['venue']->id));
		}

		if (isset($params['id']) && $params['id']) {
			$record = $this->_em->find('Dol_Model_Entity_Deal', $params['id']);
			$form->setDefaults($record->toArray());
		}
		
		$form->setDefaults(array('venue' => $this->_getParam('idVenue')));
		
		$this->view->form = $form;
		$this->_helper->viewRenderer('form');
		
		if($this->isAjax()) {
			$this->view->form->addAttribs(array('class' => 'ajaxForm'));
		}
	}

	/**
	 * Adds an address to a venue
	 *
	 * @return void
	 */
    public function addAddressAction() {
		$venue = $this->_em->find('Dol_Model_Entity_Venue', $this->_getParam('idVenue'));

    	if ($this->_request->isPost()) {
			if($this->_getParam('id')) {
				$address = $this->_em->find('Dol_Model_Entity_Address', $this->_getParam('id'));
				foreach($this->_getAllParams() as $param => $val) {
					if($address->hasAttribute($param)) {
						$address->$param = $val;
					}
				}
			}
			else
				$address = new Dol_Model_Entity_Address($this->_getAllParams());
			$this->_em->persist($address);
            $venue->address = $this->_em->merge($address);
			//$this->_em->persist($venue);
			$this->_em->flush();
			$this->_forward('index');
		}

		$this->view->form = new Dol_Model_Forms_Address();
		if($venue->address) {
			$this->view->form->populate($venue->address->toArray());
		}
		$this->_helper->viewRenderer('form');
    }

    /**
     * Marks a venue as favourite
     * 
     */
	public function favoriteVenueAction() {
		if(!Zend_Auth::getInstance()->hasIdentity()) {
			// Do something
		}
		else {
			$venue = $this->_em->find('Dol_Model_Entity_Venue', $this->_getParam('idVenue'));
			$user  = $this->_em->find('Dol_Model_Entity_User', Zend_Auth::getInstance()->getIdentity()->id);
			$user->favoriteVenue($venue);
			$this->_em->persist($user);
			$this->_em->flush();
		}
		
		// If this is an ajax request return the json object that will be run upon success
		if($this->isAjax()) {
			$return = new stdClass();
			$return->id = 'favoriteVenue';
			$return->html = '<a class="ajax right" href="/venue/unfavorite-venue/idVenue/' . $venue->id . '" id="lnkUnfavoriteVenue"><img alt="Remove from favorites" src="/img/icons/social/favorite.png" width="16" height="16" /></a>';
			die(json_encode($return));
		}
		
		// Otherwise just redirect to the venue page
		$this->_forward('index');
	}
	
	/**
     * Removes a venue from favourites
     * 
     */
	public function unfavoriteVenueAction() {
		if(!Zend_Auth::getInstance()->hasIdentity()) {
			// Do something
		}
		else {
			$venue = $this->_em->find('Dol_Model_Entity_Venue', $this->_getParam('idVenue'));
			$user  = $this->_em->find('Dol_Model_Entity_User', Zend_Auth::getInstance()->getIdentity()->id);
			$user->unfavoriteVenue($venue);
			$this->_em->persist($user);
			$this->_em->flush();
		}
		
		// If this is an ajax request return the json object that will be run upon success
		if($this->isAjax()) {
			$return = new stdClass();
			$return->id = 'favoriteVenue';
			$return->html = '<a class="ajax right" href="/venue/favorite-venue/idVenue/' . $venue->id . '" id="lnkFavoriteVenue"><img alt="Add to favorites" src="/img/icons/social/not-favorite.png" width="16" height="16" /></a>';
			die(json_encode($return));
		}
		
		// Otherwise just redirect to the venue page
		$this->_forward('index');
	}
    
	public function autocompleteCategoriesAction() {
        $safeQuery = strtolower(preg_replace('/[^[:alnum:][:space:]]/', '', $this->_getParam('term')));

        $query = "SELECT t FROM Dol_Model_Entity_DealType t 
        		  WHERE LOWER(t.name) LIKE '%". $safeQuery. "%'";

        $types = $this->_em->createQuery($query)->getResult();

        $return = array();
        foreach ($types as $type) {
            $match = new stdClass();
            $match->id    = (int) $type->id;
            $match->label = trim($type->name);
            $match->value = trim($type->name);
            $return[] = $match;
        }

        echo json_encode($return);
        
        die;
    }

}

