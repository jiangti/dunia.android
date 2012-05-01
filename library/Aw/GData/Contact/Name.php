<?php
class Aw_GData_Contact_Name extends Zend_Gdata_Extension {
    protected $_fullName;
    protected $_givenName;
    protected $_familyName;
    protected $_additionalName;
    protected $_namePrefix;
    protected $_nameSuffix;

    protected function takeChildFromDOM($child)
    {
        $absoluteNodeName = $child->namespaceURI . ':' . $child->localName;
        switch ($absoluteNodeName) {
            case $this->lookupNamespace('gd') . ':' . 'fullName':
            case $this->lookupNamespace('gd') . ':' . 'givenName':
            case $this->lookupNamespace('gd') . ':' . 'familyName':
            case $this->lookupNamespace('gd') . ':' . 'additionalName':
            case $this->lookupNamespace('gd') . ':' . 'namePrefix':
            case $this->lookupNamespace('gd') . ':' . 'nameSuffix':
                $text = new Aw_GData_Contact_Name_Text();
                $text->transferFromDOM($child);
                $this->{$child->localName} = $text;
                break;
        }
        return parent::takeChildFromDOM($child);
    }
    
    public function getFullName() {
        return $this->_fullName;
    }

    public function __toString() {
        return (string) $this->getFullName();
    }
    
    public function toArray() {
    	$data = array(
    		'fullName'   => $this->_fullName,       
			'givenName'  => $this->_givenName,      
			'familyName' => $this->_familyName,     
			'additionalName' => $this->_additionalName, 
			'namePrefix' => $this->_namePrefix,     
			'nameSuffix' => $this->_nameSuffix     
    	);
    	
    	foreach ($data as $index => $value) {
    		$data[$index] = (string) $value;
    	}
    	
    	return $data;
    }
}
