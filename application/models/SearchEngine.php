<?php

class Dol_Model_SearchEngine
{
    private $_em;

    public function __construct()
    {

        $this->_em = Zend_Registry::get('EntityManager');
    }

    public function search($searchQuery)
    {   
    	$query = "SELECT v FROM Dol_Model_Entity_Venue v 
        									JOIN v.address a
        									LEFT JOIN v.deals d
        									LEFT JOIN d.days day
        									WHERE a.longitude != '' and a.latitude != '' and v.name LIKE '%". $searchQuery. "%'";
    	
    	// If no query provided center map on Sydney CBD (hardcoded for now)
    	if ($searchQuery == '%') {
    		//$query .= ' AND a.longitude > 151.212600 AND a.longitude < 151.2126103 AND a.latitude > -33.8651073 AND a.latitude < -33.8651573';
    		$query .= ' AND a.longitude > 151.192600 AND a.longitude < 151.2226103 AND a.latitude > -33.8751073 AND a.latitude < -33.8587773 
    					AND day.day = ' . date('w');
    	}
    	
        $venues = $this->_em->createQuery($query)
                    ->setMaxResults(30)
                    ->getResult();
                    
        return $venues;
    }
}
