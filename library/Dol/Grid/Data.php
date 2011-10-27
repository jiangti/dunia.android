<?php

class Dol_Grid_Data
{
    public $head;
    public $body;

    public function set($data) {
        $this->setHead();
        $this->setBody($data);
    }

    public function setHead() 
    {
        $this->head = array();
    }

    public function setBody()
    {
        $this->body = array();
    }
}
