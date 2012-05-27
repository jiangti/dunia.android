<?php
class Model_DbTable_Row_UserHasService extends Model_DbTable_Row_RowAbstract {

    public function addAccessToken($identity) {
        $accessToken = $identity->getApi()->getAccessToken();

        if (isset($accessToken['access_token'])) {
            $this->accessToken = $accessToken['access_token'];
        } elseif (isset($accessToken['oauth_token'])) {
            $this->accessToken = $accessToken['oauth_token'];
        }

        $this->data = json_encode($accessToken);

        return $this;
    }

}