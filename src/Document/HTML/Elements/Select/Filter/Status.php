<?php

namespace Adept\Document\HTML\Elements\Select\Filter;

defined('_ADEPT_INIT') or die();

class Status extends \Adept\Document\HTML\Elements\Select
{

	public function __construct(array $attr = [], int $range = 10)
	{
		$this->label = '- Status -';

		parent::__construct($attr, []);

		$this->values[0] = 'Inactive';
		$this->values[1] = 'Active';
	}
}
