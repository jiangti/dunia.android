<?php
class Form_Deal extends Form_Abstract {
	protected $_record;
	public function init() {
		parent::init();

		$name = new Aw_Form_Element_Text('name');

		$name
			->setLabel('Name')
			->setRequired(true)
		;

		$email = new Aw_Form_Element_Email('email');
		$email->setLabel('Email');
		
		$url = new Aw_Form_Element_Url('url');
		$url
			->setLabel('Url')
			->setDescription('<a class="open" ref="">Open</a>')
		;
		
		$isChecked = new Aw_Form_Element_Checkbox('isChecked', array(
            'label' => 'Is Checked?'
		));
		
		
		$description = $url->getDecorator('description');
		$description->setOption('tag', 'span');
		$description->setOption('escape', false);
		
		$location = new Aw_Form_Element_Textarea('location');
		$location
			->setLabel('Location')
			//->setRequired(true)
			->setAttrib('style', 'height: 100px;')
		;
		
		$file = new Aw_Form_Element_File('file');
		$file->setMultiFile(3);

		$this->addElements(array($name, $email, $url, $isChecked, $location, $file));
		
		$this->setAttrib('enctype', 'multipart/form-data');
		
		foreach (range(0, 6) as $index) {
			$subForm = new Form_Deal_Detail();
			$this->addSubForm($subForm, 'detail' . $index);
			$fieldset = $subForm->getDecorator('fieldSet');
			$fieldset->setOption('escape', false);
		}

		$this->dualSubmit();
	}
	
	public function setRecord(Zend_Db_Table_Row_Abstract $record) {
		parent::setRecord($record);
		
		$this->name
			->setValue((string) $record)
			->setReadOnly(true)
		;
		
		$this->location->setValue($record->getAddress()->formatOutput(' '));
		$this->location->setReadOnly();
		
		$this->email
			->setValue($record->email)
		;
		
		$this->url
			->setValue($record->url)
		;
		
		/**
		 * Tries to populate as much as possible.
		 */
		
		$promos = $record->getPromos();
		foreach ($promos as $index => $promo) {
			$subForm = $this->getSubForm('detail' . $index);
			$subForm->setRecord($promo);
		}
	}
}