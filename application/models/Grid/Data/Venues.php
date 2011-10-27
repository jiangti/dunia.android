<?php

class Dol_Model_Grid_Data_Venues extends Dol_Grid_Data
{

    public function setHead()
    {
        $this->head = array(
            'id',
            'name',
        );
    }

    public function setBody($venues)
    {
        $this->body = array();
        foreach ($venues as $venue) {
            $this->body[] = array(
                $venue->id, 
                $venue->name
            );
        }
    }
}
