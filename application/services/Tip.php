<?php
class Service_Tip
{
    /**
     * 
     * Grabs a tip passed from a 4sq result object and saves it if it 
     * doesn't exist in the system already. 
     * @param unknown_type $idPub
     * @param unknown_type $tip
     * @throws DomainException
     */
    public function saveTipFromFoursquareResult($idPub, $tip)
    {
        $table = new Model_DbTable_Tip();
        
        $select = $table->select()->where('idFoursquare = ?', $tip->id);
        $row    = $table->fetchRow($select);

        if (!$row) {
            $db = $table->getAdapter();
            
            try {
                $db->beginTransaction();
                
                $tipRow = $table->getRow(
                    array(
                        'idPub'        => $idPub,
                        'idFoursquare' => $tip->id,
                        'text'         => $tip->text,
                        'data'         => json_encode($tip),
                        'dateUpdated'  => date("Y-m-d H:i:s"),
                    )
                );
                $tipRow->save();
            
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
            }
        }

    }
    
    public function getNonValidatedTipsByPub()
    {
        $table = new Model_DbTable_Tip();
        
        $select = $table
            ->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
            ->setIntegrityCheck(false)
            ->join(
                array('p' => 'pub'),
                'p.id = tip.idPub', 
                array('name'))
            ->where('tip.validated = 0');
        
        $tips = $table->fetchAll($select);
        
        $return = array();
        foreach ($tips as $tip) {
            $return[$tip['name']][] = $tip;
        }
        
        return $return;
    }
}