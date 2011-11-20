<?php
abstract class Model_Parser {
	/**
	 * 
	 * @param string $html
	 * @return array(pub,promo)|false
	 */
    abstract public function parse($html);
}