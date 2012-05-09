<?php

class UserController extends Model_Controller_Action
{

    public function indexAction() {

    }

    public function loginAction() {
        $bootstrap  = $this->getInvokeArg('bootstrap');
        $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

        $this->view->foursquareUrl = $foursquare->getAuthorizeUrl('http://localhost/user/login-foursquare');
    }

    public function loginFoursquareAction() {

        if ($code = $this->_getParam('code')) {
            $bootstrap  = $this->getInvokeArg('bootstrap');
            $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

            $token = $foursquare->getAccessToken($code, 'http://localhost/user/login-foursquare');
            $this->view->accessToken = $token->access_token;
        }
    }

    public function crawlCheckinsAction() {
        $bootstrap  = $this->getInvokeArg('bootstrap');
        $foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();

        $foursquare->setAccessToken('0P2OSBQ0MJTILVEB3WK0TNOY2QFI1EF0NTMWXA1B0PDHDMVH');

        $checkins = $foursquare->get('/users/self/checkins', array(
            'limit'      => 250,
        ));

        $totalCheckins = $checkins->response->checkins->count;
        $offset = 0;

        $data = array();
        while ($totalCheckins) {
            foreach ($checkins->response->checkins->items as $checkin) {
                if (isset($data[$checkin->venue->id])) {
                    $data[$checkin->venue->id]['count']++;
                } else {
                    $data[$checkin->venue->id] = array (
                        'count' => 1,
                        'name'  => $checkin->venue->name,
                        'icon'  => $checkin->venue->categories[0]->icon
                    );
                }
            }

            $offset += 250;
            if ($totalCheckins > 250) {
                $totalCheckins -= 250;
            } else {
                $totalCheckins = 0;
            }

            $checkins = $foursquare->get('/users/self/checkins', array(
                'limit'  => 250,
                'offset' => $offset
            ));
        }

        uasort($data, function ($a, $b)
            {
                if ($a['count'] == $b['count']) {
                    return 0;
                }
                return ($a['count'] > $b['count']) ? -1 : 1;
            });

        $this->view->data = $data;
    }
}
