<?php
class AdminController extends Zend_Controller_Action {
	public function hancockAction() {
		
		
		$file = fopen('/home/jiangti/530-project.csv', 'r');
		while ($row = fgetcsv($file)) {
			
		}
	}
}