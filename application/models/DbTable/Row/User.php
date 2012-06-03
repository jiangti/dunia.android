<?php
class Model_DbTable_Row_User extends Model_DbTable_Row_RowAbstract {

    public function hasService($name) {
        $db = Zend_Db_Table::getDefaultAdapter();

        foreach ($this->findManyToManyRowset('Model_DbTable_Service', 'Model_DbTable_UserHasService') as $service) {
            if ($service->name == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $identity
     *
     * Refreshes access tokens for the passed service
     */
    public function updateService($identity) {
        foreach ($this->findDependentRowset('Model_DbTable_UserHasService') as $service) {
            if ($identity->getId() == $service->uuid) {
                $service = $service->addAccessToken($identity);
                $service->save();
                return true;
            }
        }
    }

    /**
     * @param $identity
     *
     * Links the passed service with the user
     */
    public function linkService($identity) {
        $userHasServiceTable = new Model_DbTable_UserHasService();
        $serviceTable        = new Model_DbTable_Service();

        $service = $serviceTable->fetchRow(Zend_Db_Table::getDefaultAdapter()->quoteInto('name = ?', $identity->getName()));

        $row = $userHasServiceTable->createRow();

        $row->uuid = $identity->getId();
        $row = $row->addAccessToken($identity);

        $row->idService = $service->id;
        $row->idUser    = $this->id;

        $row->save();
        return true;
    }

    public function addService($serviceName, $accessToken, $idUser) {
        $userHasServiceTable = new Model_DbTable_UserHasService();
        $serviceTable        = new Model_DbTable_Service();

        $service = $serviceTable->fetchRow(Zend_Db_Table::getDefaultAdapter()->quoteInto('name = ?', $serviceName));

        $row = $userHasServiceTable->createRow();

        $row->uuid        = $idUser;
        $row->accessToken = $accessToken;
        $row->idService   = $service->id;
        $row->idUser      = $this->id;

        $row->save();
        return true;
    }

}