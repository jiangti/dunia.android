<?php
/**
 * http://www.sydneyhappyhour.com
 */
class Model_Parser_SydneyHappyHour extends Model_Parser {
    public function parse($html) {
		preg_match('/Happy Hours and Promotions(.*)You can start editing here/ms', $html, $match);
		if ($match) {
			/**
			 * The generated html is so fucked up, <p> ends with </div>
			 */
			$fixedHtml = str_replace(array('<p>', '<div id="small_vert_space"></div>'), array('<div>', ''), $match[1]);
			$html = str_replace($match[1], $fixedHtml, $html);
			
			$html = str_replace('<br />', '', $html);		
			
			
			
			$xpath = Zend_Dom_Query_Css2Xpath::transform('#pub_float_left');
			
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			@$dom->loadHTML($html);
			$dom->normalizeDocument();
			
			$xml = simplexml_import_dom($dom);
			
			$elements = $xml->xpath($xpath);
			if (count($elements)) {
				$pub = new Model_Parser_Data_Pub();
				$promo = array();
				foreach ($elements as $index => $element) {
					if ($index == 0) {
						$pub->title = (string) $element->div['title'];
						$pub->address = (string) $element;
						$pub->phone = ((String) $element->p);
						
						/**
						 * Cleaning out dumb comments from the original data.
						 */
						if (preg_match('/The Sydney Happy Hour(.*)below./ms', $pub->phone, $match)) {
							$pub->phone = str_replace($match[0], '', $pub->phone);
						}
						
						$unknown = (string) $element->p->a[0];
						if (filter_var($unknown, FILTER_VALIDATE_EMAIL)) {
							$pub->email = $unknown;
							$pub->url = (string) $element->p->a[1];
						} else {
							$pub->url = $unknown;
						}
					} else {
						$promo = new Model_Parser_Data_Pub_Promo();
						$promo->day = (string) $element->div->b; 
						$promo->dealString = (string) $element->div;
						$pub->promo[] = $promo->trim();
					}
				}
			}
			return $pub->trim();
		} else {
			return false;
		}
    }
}