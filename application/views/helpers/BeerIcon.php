<?php

class Zend_View_Helper_BeerIcon extends Zend_View_Helper_Abstract
{
    function beerIcon($itsOn, $suffix = '') {
        
        switch ($itsOn) {
            case 'now':
                return '/img/icons/markers/half' . $suffix . '.png';
            case 'earlier':
                return '/img/icons/markers/empty' . $suffix . '.png';
            case 'later':
                return '/img/icons/markers/full' . $suffix . '.png';
        }
    }
}

