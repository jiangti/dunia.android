<?php

namespace D2Test\Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Dol_Model_Entity_TelephoneProxy extends \Dol_Model_Entity_Telephone implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function __set($name, $value)
    {
        $this->_load();
        return parent::__set($name, $value);
    }

    public function __get($name)
    {
        $this->_load();
        return parent::__get($name);
    }

    public function toArray()
    {
        $this->_load();
        return parent::toArray();
    }

    public function hasAttribute($attr)
    {
        $this->_load();
        return parent::hasAttribute($attr);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'number', 'type');
    }
}