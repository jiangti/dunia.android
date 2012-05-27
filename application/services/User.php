<?php
class Service_User extends Aw_Service_ServiceAbstract
{

    public function login($params) {
        $auth = Aw_Auth::getInstance();

        if (isset($params['provider'])) {
            switch ($params['provider']) {
                case "facebook":
                    if ($params['code']) {
                        $adapter = new Aw_Auth_Adapter_Facebook($params['code']);
                    }
                    break;
                case "twitter":
                    if ($params['oauth_token']) {
                        $adapter = new Aw_Auth_Adapter_Twitter($params);
                    }
                    break;
                case "google":
                    if ($params['code']) {
                        $adapter = new Aw_Auth_Adapter_Google($params['code']);
                    }
                    break;

            }

            $result = $auth->authenticate($adapter);

            // Link this service to a user if it exists or create a new one if not
            $this->linkSessionServices();

            return $result;
        }

        return false;
    }

    public function linkSessionServices() {
        $auth = Aw_Auth::getInstance();
        foreach ($auth->getIdentity() as $identity) {
            $id      = $identity->getId();
            $profile = $identity->getApi()->getProfile();
            $email   = isset($profile['email']) ? $profile['email'] : null;

            // Try to find a user match based on service ID and email
            $user = $this->findUserByServiceAndEmail($id, $identity->getName(), $email);

            if ($user) {

                if ($user->hasService($identity->getName())) {
                    // If we have a user and this service is already linked, update accessToken info
                    $user->updateService($identity);
                } else {
                    // Otherwise link the service to the user's account
                    $user->linkService($identity);
                }
            } else {
                // Create the user and link this service to the new account
                $user = $this->createUserFromService($identity);
            }
            $session = new Zend_Session_Namespace('user');
            $session->user = $user;
        }
    }

    public function findUserByServiceAndEmail($uuid, $service, $email) {
        $userTable = new Model_DbTable_User();
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $select = $userTable
            ->select(false)
            ->setIntegrityCheck(false)
            ->from(array('u' => 'user'))
            ->joinLeft(array('uhs' => 'userHasService'), 'u.id = uhs.idUser', array())
            ->joinLeft(array('s' => 'service'), 's.id = uhs.idService', array())
            ->where($db->quoteInto('s.name = ?', $service) . ' AND ' . $db->quoteInto('uhs.uuid = ?', $uuid))
        ;

        if ($email) {
            $select->orWhere('u.email = ?', $email);
        }

        return $userTable->fetchRow($select);
    }

    public function createUserFromService($identity) {
        $userTable = new Model_DbTable_User();

        $user = $userTable->createRow($identity->getApi()->getUserData());
        $user->save();

        $user->linkService($identity);
    }

    /**
     * @param unknown_type $query
     * @return Zend_Db_Table_Select
     */
    public function searchPub($query)
    {
        $pubTable = new Model_DbTable_Pub();
        
        $select = $pubTable->select(false);
        $select->from(array('p' => 'pub'));
        
        if ($query) {
            $select->where('p.name like ?', sprintf('%s%%', $query));
            $select
            ->setIntegrityCheck(false)
            ->joinLeft(array('a' => 'address'), 'p.idAddress = a.id', array(
            			'longitude',
                        'latitude',
                        ))
            ->orWhere(sprintf('a.address1 like "%%%s%%" or a.postcode = "%s" or a.town like "%%%s%%"', $query, $query, $query))
            ;
        }
        
        return $select;
    }
    

}
