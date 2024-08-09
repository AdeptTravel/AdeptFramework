<?php

namespace Adept\Document\HTML\Elements\DropDown;

defined('_ADEPT_INIT') or die();

class Year extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [], int $range = 10)
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Year';
		$this->placeholder = 'Year';

		parent::__construct($attr);
	}

	public 
}
