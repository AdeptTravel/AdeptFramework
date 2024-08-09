<?php

namespace Adept\Document\HTML\Elements\Form\DropDown;

defined('_ADEPT_INIT') or die();

class Month extends \Adept\Document\HTML\Elements\Form\DropDown
{

	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		$this->values[1] = 'January';
		$this->values[2] = 'February';
		$this->values[3] = 'March';
		$this->values[4] = 'April';
		$this->values[5] = 'May';
		$this->values[6] = 'June';
		$this->values[7] = 'July';
		$this->values[8] = 'August';
		$this->values[9] = 'September';
		$this->values[10] = 'October';
		$this->values[11] = 'November';
		$this->values[12] = 'December';
	}
}
