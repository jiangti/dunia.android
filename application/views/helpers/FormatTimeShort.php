<?php
class Zend_View_Helper_FormatTimeShort extends Zend_View_Helper_Abstract
{
    public function formatTimeShort($date)
    {
        $datetime = strtotime($date);
        if ((int) date('i', $datetime)) {
            return date('g:ia', $datetime);
        } else {
            return date('ga', $datetime);
        }
    }
}