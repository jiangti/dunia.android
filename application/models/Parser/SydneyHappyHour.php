<?php
/**
 * http://www.sydneyhappyhour.com
 */
class Model_Parser_SydneyHappyHour extends Model_Parser {
    public function parse($html) {
		preg_match('/Happy Hours and Promotions(.*)You can start editing here/ms', $html, $match);
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
			$pubData = array();
			$promo = array();
			foreach ($elements as $index => $element) {
				if ($index == 0) {
					$pubData['title'] = $element->div['title'];
					$pubData['address'] = (string) $element;
					$pubData['phone'] = ((String) $element->p);
					
					$unknown = (string) $element->p->a[0];
					if (filter_var($unknown, FILTER_VALIDATE_EMAIL)) {
						$pubData['email'] = $unknown;
						$pubData['url'] = (string) $element->p->a[1];
					} else {
						$pubData['url'] = $unknown;
					}
				} else {
					$data = array();
					$data['day'] = (string) $element->div->b; 
					$data['dealString'] = (string) $element->div;
					$promo[] = array_map('trim', $data);
				}
			}
			
			$pubData = array_map('trim', $pubData);
			print_r($pubData);
			print_r($promo); 
		}
    }
}