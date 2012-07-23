<?php
/**
 * 
 * @author jiangti.wan.leong@gmail.com
 * 
 * Truncates a string to the word, but uses character counter. Adds a horizontal eclipse.
 *
 */
class Aw_View_Helper_TokenTruncate extends Zend_View_Helper_Abstract {
	
	public function tokenTruncate($string, $your_desired_width) {
		$parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
		$parts_count = count($parts);
	
		$eclipse = false; 
		
		$length = 0;
		$last_part = 0;
		for (; $last_part < $parts_count; ++$last_part) {
			$length += strlen($parts[$last_part]);
			if ($length > $your_desired_width) {
				$eclipse = true;
				break;
			}
		}
	
		$string = implode(array_slice($parts, 0, $last_part));
		return ($eclipse ? $string . '&#8230;': $string);
	}

}