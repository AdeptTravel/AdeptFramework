<?php

namespace Adept\Document\HTML\Elements\Select;

defined('_ADEPT_INIT') or die();

class Year extends \Adept\Document\HTML\Elements\Select
{

	public function __construct(array $attr = [], int $range = 10)
	{
		$this->label = 'Year';

		parent::__construct($attr, []);

		$now = (int)date('Y');
		$end = (int)date('Y', strtotime($range . ' years'));

		if ($now < $end) {
			for ($i = $now; $i < $end; $i++) {
				$this->values[$i] = $i;
			}
		} else {
			for ($i = $now; $i > $end; $i--) {
				$this->values[$i] = $i;
			}
		}
	}
}
