<?php

namespace Adept\Document\HTML\Elements\Select;

defined('_ADEPT_INIT') or die();

class Day extends \Adept\Document\HTML\Elements\Select
{

	public function __construct(array $attr = [])
	{
		$this->label = 'Day';

		parent::__construct($attr, []);

		for ($i = 1; $i <= 31; $i++) {
			$this->values[$i] = $i;
		}
	}
}
