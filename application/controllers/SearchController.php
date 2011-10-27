<?php 

class SearchController extends Dol_Controller
{
    public function indexAction()
    {
        $form = new Dol_Model_Forms_Search();
        $venues = array();

        if($this->_request->isPost()) {
            $post = $this->_request->getParams();

            if($form->isValid($post)) {

                $searchQuery = $form->searchQuery->getValue();
                $searchEngine = new Dol_Model_SearchEngine();

                $result = $searchEngine->search($searchQuery);
            }
        }

        $this->view->result = Dol_Grid_Data_Manager::create('Dol_Model_Grid_Data_SearchResult', $result); 
        $this->view->form = $form;
    }
}
