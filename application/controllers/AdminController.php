<?php
class AdminController extends Zend_Controller_Action {
	public function hancockAction() {
		$pubService = new Service_Pub();


		$file = fopen('/home/jiangti/Downloads/pc_full_lat_long.csv', 'r');

		$counter = 0;

		while ($row = fgetcsv($file)) {
		    if ($counter > 0) {
		       $postCode = $row[0];
		    }
		    $counter++;
		}

		$file = fopen(APPLICATION_ROOT . '/data/google-refine/530-project.csv', 'r');
		while ($row = fgetcsv($file)) {

			$pub = new Model_Pub();

			$data['name'] = $row[0];
			$data['email'] = $row[2];
			$data['url'] = $row[3];

			$pub->setFromArray($data);
			$addressData = Model_Address::extract($row[4]);

			//$addressData = array(
			//	'address1'   => $addressRow[],
			//	'address2'   => $addressRow[],
			//	'city'       => $addressRow[],
			//	'postcode'   => $addressRow[],
			//	'town'       => $addressRow[],
			//	'state'      => $addressRow[],
			//	'country'    => $addressRow[],
			//	'latitude'   => $addressRow[],
			//	'longtitude' => $addressRow[]
			//);


			//$address = new Model_Address();
			//$address->setFromArray($addressData);

			//$pub->setAddress($address);

//			$pubService->savePub($pub);
		}
        exit;
	}

	public function pubImportAction() {
        $table = new Model_DbTable_Dirty();

        $select = $table->select();
        $select->group('pub');

        foreach ($table->fetchAll($select) as $row) {
            var_dump($row); exit;
        }

	}
}