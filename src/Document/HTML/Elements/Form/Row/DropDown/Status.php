<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown;

defined('_ADEPT_INIT') or die();

class Status extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{

	public string $name  = 'status';
	public string $label = 'Status';

	public function __construct(array $attr = [], int $range = 10)
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Status';

		parent::__construct($attr, []);
	}
}
