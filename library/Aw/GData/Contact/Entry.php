<?php
class Aw_GData_Contact_Entry extends Zend_Gdata_Entry {
    protected $_name;
    protected $_emails = array();
    protected $_ims = array();
    protected function takeChildFromDOM($child)
    {
        $absoluteNodeName = $child->namespaceURI . ':' . $child->localName;
        switch ($absoluteNodeName) {
            case $this->lookupNamespace('gd') . ':' . 'name':
                $name = new Aw_GData_Contact_Name();
                $name->transferFromDOM($child);
                $this->_name = $name;
                break;
            case $this->lookupNamespace('gd') . ':' . 'email':
                $email = new Aw_GData_Contact_Email();
                $email->transferFromDOM($child);
                $this->_emails[] = $email;
                break;
        }
        return parent::takeChildFromDOM($child);
    }

    public function getName() {
        return $this->_name;
    }
    
    public function getEmails() {
    	return $this->_emails;
    }
    
    public function getEmail() {
    	return current($this->getEmails());
    }
    
    public function getIms() {
    	
    }
    
}
