<?php
class Model_DbTable_Row_Pub extends Model_DbTable_Row_RowAbstract {
    protected $_address;
    protected $_pubType;
    
    protected $_referenceMap    = array(
        'address' => array(
        	'columns'       => 'idAddress',
        	'refTableClass' => 'Model_DbTable_Address',
        	'refColumns'    => 'id'
        ),
    );
    
    public function getPermaLink() {
    	return sprintf('/pub/overview/id/%d/name/%s', $this->id, urlencode(preg_replace('/[^a-z^A-Z^0-9]/', '_', trim($this->name))));
    }
    
    public function mergeMailShare(Model_DbTable_Row_MailShare $mailshare) {
    	foreach ($mailshare->getImages() as $image) {
	    	$this->addImage($image);
    	}
    }
    
	public function setAddress(Model_DbTable_Row_Address $address) {
	    // This should copy over and reuse the existing record.
	    if ($address1 = $this->findParentRow(new Model_DbTable_Address())) {
	        $addressData = $address->toArray();
	        unset($addressData['id']);
	        $address1->setFromArray($addressData);
	        $address = $address1;
	    }

	    $this->_address = $address;
	}
	
	/**
	 * Expands into the string
	 * @param unknown_type $str
	 */
	public function savePromoByParse($str, $days) {
		/**
		 * Clears up all line endings
		 */
		$promoTable = new Model_DbTable_Promo();
		
		$promo = $promoTable->createRow();
		$promo->day = preg_replace('/\W,/', '', $days);

		foreach (explode("\n", $str) as $lineIndex => $line) {
			$line = trim($line);
			
			if (!$line) continue;
			
			if ($lineIndex == 0) {
				$promo->parseTime($line);
			} else {
				$promoClone = clone $promo;
				
				$price = explode(" ", $line);
				$price = $price[0];
				$promoClone->price = preg_replace('/[^0-9\.]/', '', $price);
				$promoClone->price = (float) str_replace('$', '', $promoClone->price);
				/**
				 * LiquorType parser.
				 */
				
				$promoClone->save();
				$this->addPromo($promoClone);
				
				if ($liquorType = Model_LiquorType::parse($line)) {

					if ($liquorSize = Service_LiquorSize::parse($line)) {
						$promoClone->addLiquorType($liquorType, $liquorSize);
					} else {
						$promoClone->addLiquorType($liquorType);
					}
					
				} else {
					throw new Exception('Could not identify the liquorType.');
				}
				
				
 			}
		}
		
	}
	
	public function getPromos() {
		return $this->findManyToManyRowset(new Model_DbTable_Promo(), new Model_DbTable_PubHasPromo());
	}
	
	public function addDealFromArray($data) {
	    $days = array();
	    foreach ($data['days'] as $day) {
	    	$dayEnum = new Model_Day($day);
	    	$days[] = $dayEnum->getAbbr();
	    }
	    
	    $service = new Service_Time();
	    
	    $service->setTime($data['time']);
	    
		$array['timeStart']   = $service->getStart();
		$array['timeEnd']     = $service->getEnd();
		$array['day']         = $days;
		$array['price']       = $data['value'];
		$array['description'] = $data['description'];
		
		$promo = Model_DbTable_Promo::getRow($array);
		$promo->save();
		
		foreach ($data['liquorType'] as $liquorType) {
			$promo->addLiquorTypeById($liquorType, $data['liquorSize']);
		}
		
		$this->addPromo($promo);
	}
	
	public function resetPromo() {
		foreach ($this->getPromos() as $promo) {
			$promo->delete();
		}
	}
	
	public function addPromo(Model_DbTable_Row_Promo $promo) {
		$data['idPub'] = $this->id;
		$data['idPromo'] = $promo->id;
		
		$row = Model_DbTable_PubHasPromo::getRow($data);
		$row->save();
		
		return $row;
	}

	/**
	 * Explicit optimization this takes n+1 problem away. Not suitable for very large dataset.
	 * @return Model_DbTable_Row_Address|null
	 */
	public function getAddress() {
		//return $this->findParentRow(new Model_DbTable_Address());
		if ($this->_address) { } else {
			foreach ($this->_rowset->getLoadRows(new Model_DbTable_Address(), 'address') as $row) {
				if ($row->id == $this->idAddress) {
					$this->_address = $row;
					break;
				}
			}
	    }
	    return $this->_address;
	}

    public function getPubType() {
        if ($this->_pubType) { } else {
            foreach ($this->_rowset->getLoadRows(new Model_DbTable_PubType(), 'pubType') as $row) {
                if ($row->id == $this->idPubType) {
                    $this->_pubType = $row;
                    break;
                }
            }
        }
        return $this->_pubType;
    }
	
	public function getImageDirectory() {
		$directory = sprintf(APPLICATION_ROOT . '/public/images/pub/%d', $this->id);
		if (!file_exists($directory)) {
			mkdir($directory, 0777, true);
		}
		return $directory;
	}
	
	public function addImage($fullPath) {
		$directory = $this->getImageDirectory();
		copy($fullPath, $directory . '/' . basename($fullPath));
	}
	
	public function getImages() {
		$files = glob($this->getImageDirectory() . '/*');
		foreach ($files as $index => $file) {
			$files[$index] = basename($file);
		}
		return $files;
	}
	
	public function getImagesWebUri() {
		$files = glob($this->getImageDirectory() . '/*');
		foreach ($files as $index => $image) {
			$files[$index] = str_replace(DOC_ROOT, '', $image);
		}
		return $files;
	}
	
	public function _save() {
	    parent::_save();
	    if ($this->_address) {
	        if (!$this->_address->id) {
	            $this->_address->save();
	        }

	        if ($this->_address->id != $this->idAddress) {
	            $this->idAddress = $this->_address->id;
	            $this->save();
	        }
	    }
	}
	
	public function __toString() {
		return $this->name;
	}
}