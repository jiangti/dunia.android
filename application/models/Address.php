<?php
class Model_Address extends Aw_Model_ModelAbstract {

    public $id;
    public $buildingName;
    public $address1;
    public $address2;
    public $city;
    public $postcode;
    public $town; // Also suburb
    public $state;
    public $country;
    public $latitude;
    public $longtitude;


	/**
	 *
	 * The smart address extractor. Just a utlity, so nothing to do with the actualyl model.
	 * @param unknown_type $string
	 * @return Model_Address|false
	 */
	public static function extract($string) {
	    $string = str_replace("'", '"', $string);
	    $data = json_decode($string);
	    if (isset($data->results[0])) {

            $address = new Model_Address();

    	    $results = $data->results[0];

        	foreach ($results->address_components as $item) {
        	    switch ($item->types[0]) {
        	        case 'street_number':
        	            $address->address1 .= $item->long_name;
        	            break;
        	        case 'route':
        	            $address->address1 .= $item->long_name;
        	            break;
        	        case 'locality':
        	            $address->town = $item->long_name;
        	            break;
        	        case 'administrative_area_level_1':
        	            $address->state = $item->short_name;
        	            break;
        	        case 'country':
        	            $address->country = $item->long_name;
        	            break;
        	        case 'postal_code':
        	            $address->postcode = $item->long_name;
        	            break;
        	        case 'neighborhood':
        	            $address->address2 .= $item->long_name;
        	            break;
        	        case 'subpremise':
        	            $address->address1 .= sprintf('%s - ', $item->long_name);
        	            break;
        	        case 'point_of_interest':
        	        case 'sublocality':
        	            break;
        	        case 'establishment':
        	            $address->buildingName = $item->long_name;
        	            break;
        	        default:
        	            break;
        	    }
        	}
        	$location = $results->geometry->location;

	    } else return false;
	}

}