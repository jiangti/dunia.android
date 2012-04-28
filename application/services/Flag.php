<?php
class Service_Flag
{
	public function getNonModeratedFlags() {
        $flagTable = new Model_DbTable_Flag();

        $select = $flagTable->select(false)
            ->setIntegrityCheck(false)
            ->from(array('f' => 'flag'))
            ->join(array('p' => 'pub'), 'p.id = idPub', array('name', 'idPub' => 'id'))
            ->joinLeft(array('u' => 'user'), 'u.id = idUser', array('login'))
            ->where('status = ?', Model_Flag::FLAG_STATUS_NOT_PROCESSED);

        return $select;
    }

    public function create(array $params) {
        $flag = new Model_Flag();
        $data = array();

        $data['idPub']     = $params['idPub'];
        $data['idUser']    = null; // @Todo: Add current user once we have ACL
        $data['type']      = $params['type'];
        $data['status']    = Model_Flag::FLAG_STATUS_NOT_PROCESSED;
        $data['dateAdded'] = date('Y-m-d H:i:s');

        switch ($params['type']) {
            case Model_Flag::FLAG_TYPE_ADDRESS:
                $data['data'] = json_encode($params['address']);
                break;
            case Model_Flag::FLAG_TYPE_PROMO:
                $data['data'] = json_encode($params['promo']);
                break;
            default:
                $data['data'] = '';
        }

        $flag->setFromArray($data);
        return $flag;
    }
}