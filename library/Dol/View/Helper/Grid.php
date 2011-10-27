<?php

require_once 'Zend/View/Helper/Abstract.php';

class Dol_View_Helper_Grid extends Zend_View_Helper_Abstract
{
    public function grid($name, Dol_Grid_Data $data = null)
    {
        if(!$data) {
            return null;
        }

        $output = array();
        $output[] = '<table>';
        $output[] = '<thead>';
        $output[] = '<tr>';
        
        foreach($data->head as $th) {
            $output[] = '<th>';
            $output[] = $th; 
            $output[] = '</th>'; 
        }

        $output[] = '</tr>';
        $output[] = '</thead>';
        $output[] = '<tbody>';
        foreach ($data->body as $tr) {
            $output[] = '<tr>';
            foreach ($tr as $td) {
                $output[] = '<td>';
                $output[] = $td;
                $output[] = '</td>';
            }    
            $output[] = '</tr>';

        }
        $output[] = '</tbody>';
        $output[] = '</table>';

        return implode('', $output);
    }
}
