<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown;

defined('_ADEPT_INIT') or die();

class Status extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{

	public string $name  = 'status';
	public string $label = 'Status';
	public bool $filter  = false;
	public bool $archive = false;
	public bool $trash   = true;
	public bool $lost    = false;

	public function __construct(array $attr = [], int $range = 10)
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Status';

		parent::__construct($attr, []);

		$this->values[0] = 'Inactive';
		$this->values[1] = 'Active';

		if ($this->archive) {
			$this->values[2] = 'Archive';
		}

		if ($this->trash) {
			$this->values[3] = 'Trashed';
		}

		if ($this->lost) {
			$this->values[4] = 'Lost';
		}
	}
}
