<?php
class Model_DbTable_Address extends Model_DbTable_TableAbstract {
	protected $_name = 'address';

	protected $_rowClass = 'Model_DbTable_Row_Address';

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $string
	 * @return Model_DbTable_Address
	 */
	public static function extract($string) {
	    $string = str_replace("'", '"', $string);
	    $data = json_decode($string);
	    if (isset($data->results[0])) {

	        $addressTable = new static();
	        $address = $addressTable->createRow();

	        $results = $data->results[0];

	        foreach ($results->address_components as $item) {
	            switch ($item->types[0]) {
	                case 'street_number':
	                    $address->address1     .= ' ' . $item->long_name;
	                    break;
	                case 'route':
	                    $address->address1     .= ' ' . $item->long_name;
	                    break;
	                case 'locality':
	                    $address->town         = $item->long_name;
	                    break;
	                case 'administrative_area_level_1':
	                    $address->state        = $item->short_name;
	                    break;
	                case 'country':
	                    $address->country      = $item->long_name;
	                    break;
	                case 'postal_code':
	                    $address->postcode     = $item->long_name;
	                    break;
	                case 'neighborhood':
	                    $address->address2     .= $item->long_name;
	                    break;
	                case 'subpremise':
	                    $address->address1     .= sprintf('%s - ', $item->long_name);
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

	        $address->latitude   = $location->lat;
	        $address->longtitude = $location->lng;

            $address->address1 = trim($address->address1);
	        return $address;

	    } else return false;
	}
}