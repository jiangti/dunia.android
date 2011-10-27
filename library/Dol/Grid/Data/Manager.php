<?php

class Dol_Grid_Data_Manager
{
    public static function create($dataClass, $data)
    {
        $gridData = new $dataClass;

        $gridData->set($data);

        return $gridData;
    }
}
