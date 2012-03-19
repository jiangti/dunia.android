<?php

class Zend_View_Helper_Currency extends Zend_View_Helper_Abstract
{
    function currency($value, $zeroLabel=null, $decimals=null) {
        
        $symbol = '$';
        $value = str_replace(array(',', ' ','$'), '', $value);
        if ($value == '' or $value == 0) {
            if (is_numeric($zeroLabel)) {
                return $symbol.number_format((float) $zeroLabel, ($zeroLabel == (int) $zeroLabel ? 0 : 2), '.', ',');
            } else {
                return $zeroLabel;
            }
        } else { if ($decimals== null) return $symbol.number_format((float) $value, ($value == (int) $value ? 0 : 2), '.', ',');
				 else return $symbol.number_format((float) $value, $decimals, '.', ',');
        }
    }
}

