<?php
abstract class Aw_Model_ModelAbstract {
	protected static $_classesReflection = array();
	/**
	 * Runs trim on all public elements.
	 */
	public function trim() {
		$class = new ReflectionClass($this);
		$properties = $class->getProperties();
		foreach ($properties as $property) {
			if ($property->isPublic()) {
				$value = $this->{$property->name};
				if (is_string($value)) {
					$this->{$property->name} = trim($value);
				}
			}
		}
		return $this;
	}

    protected function _getProperties($type = ReflectionProperty::IS_PUBLIC) {
    	$reflection = new ReflectionClass($this);
    	$properties = $reflection->getProperties($type);
    	return $properties;
    }

    protected function _getPropertyIndex($type = ReflectionProperty::IS_PUBLIC) {
    	$properties = $this->_getProperties();

		$propertyIndex = array();
		foreach ($properties as $property) {
			$propertyIndex[] = $property->name;
		}

		return $propertyIndex;
    }

	public function getArray() {
		$propertyIndex = $this->_getPropertyIndex();

		$data = array();

		foreach ($propertyIndex as $index) {
		    if (is_a($this->{$index}, 'Aw_Model_ModelAbstract')) {
		        $data[$index] = $this->{$index}->getArray();
		    } else {
			    $data[$index] = $this->{$index};
		    }
		}

		return $data;
	}

	public function setFromArray($array) {
		$proprtyIndex = $this->_getPropertyIndex();
		foreach ($array as $index => $value) {
			if (in_array($index, $proprtyIndex)) {
				/**
				 * Only allow setting of public varaiables..
				 */
				$this->{$index} = $value;
			} else {
				throw new DomainException(sprintf('Property %s is not a public variable in the model class.', $index));
			}
		}
	}
}