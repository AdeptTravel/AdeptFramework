<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Filter;

defined('_ADEPT_INIT') or die();

class Block extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [], int $range = 10)
	{
		parent::__construct($attr);

		$this->filter 	 = false;
		$this->emptyDisplay = '-- ' . $this->placeholder . ' --';
		$this->values[0] = 'No';
		$this->values[1] = 'Yes';
	}
}
