<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Filter;

defined('_ADEPT_INIT') or die();

class Status extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [], int $range = 10)
	{
		parent::__construct($attr);

		$this->filter 	 = false;
		$this->emptyDisplay = '-- Select Status --';
		$this->values[0] = 'Inactive';
		$this->values[1] = 'Active';
		$this->values[2] = 'Archive';
		$this->values[3] = 'Trashed';
		$this->values[4] = 'Lost';
	}
}
