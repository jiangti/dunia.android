<?php
class MailshareController extends Zend_Controller_Action {
    public function deleteAction() {

        $service = new Service_Mailshare();
        $service->delete($this->_getParam('id'));

        if ($this->_request->isXmlHttpRequest()) {
            exit;
        }
    }

    public function rotateAction() {

        $service = new Service_Mailshare();

        switch ($rotate = $this->_getParam('rotate')) {
            case 'left':
                $direction = Service_Mailshare::LEFT;
                break;
            case 'right':
                $direction = Service_Mailshare::RIGHT;
                break;
            default: break;
        }

        $service->rotateImage($this->_getParam('imagePath'), Service_Mailshare::LEFT);
        return exit(sha1(microtime(true)));
    }

    public function mergeAction() {

        $form = new Form_MailShare();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $service = new Service_Mailshare();
            $pubRow = $service->merge($form->getValues());

            if ($this->_request->isXmlHttpRequest()) {
                echo json_encode($pubRow->toArray());
                exit;
            } else {
                $url = $this->view->url(array('controller' => 'pub', 'action' => 'share', 'id' => $pubRow->id));
                $this->_redirect($url);
            }

        }
    }

    public function uploadAction() {
        $service = new Service_Mailshare();

        if ($id = $service->add($this->_request->getPost())) {
            try {
                $directory = sprintf(APPLICATION_ROOT . '/public/mail/%d', $id);
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }
                move_uploaded_file($_FILES["file"]["tmp_name"], $directory.'/photo.jpg');
                exit;
            } catch (Exception $e) {
                throw $e;
            }
        } else {
            throw new Zend_Application_Exception('Cannot add new mail shared record.');
        }
    }
}