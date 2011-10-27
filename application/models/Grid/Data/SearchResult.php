<?php

class Dol_Model_Grid_Data_SearchResult extends Dol_Grid_Data {
	
    public function setHead() {
        $this->head = array(
            'type',
            'name'
        );
    }

    public function setBody($results)
    {
        $this->body = array();
        foreach ($results as $result) {
            $this->body[] = array(
                $result->id, 
                $result->name
            );
        }
    }
}
