<?php

namespace Adept\Document\HTML\Elements\Form\DropDown;

defined('_ADEPT_INIT') or die();

class Day extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		for ($i = 1; $i <= 31; $i++) {
			$this->values[$i] = $i;
		}
	}
}
