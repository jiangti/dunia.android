<?php 
class Zend_View_Helper_FormatDate extends Zend_View_Helper_Abstract
{
    public function formatDate($date, $format = "d/m/Y")
    {
        $datetime = strtotime($date);
        return date($format, $datetime);
    }
}