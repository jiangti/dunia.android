<?php
class AdminController extends Zend_Controller_Action {
	public function hancockAction() {
		$pubService = new Service_Pub();
		
		$file = fopen(APPLICATION_ROOT . '/data/google-refine/530-project.csv', 'r');
		while ($row = fgetcsv($file)) {
			
			$pub = new Model_Pub();
			
			$data['name'] = $row[0];
			$data['email'] = $row[2];
			$data['url'] = $row[3];
			
			$pub->setFromArray($data);
			
			$addressData = array(
				'address1'   => $addressRow[], 
				'address2'   => $addressRow[],
				'city'       => $addressRow[],
				'postcode'   => $addressRow[],
				'town'       => $addressRow[],
				'state'      => $addressRow[],
				'country'    => $addressRow[],
				'latitude'   => $addressRow[],
				'longtitude' => $addressRow[]
			);
			
			$address = new Model_Address();
			$address->setFromArray($addressData);
			
			$pub->setAddress($address);
			
			$pubService->savePub($pub);
		}
	}
}