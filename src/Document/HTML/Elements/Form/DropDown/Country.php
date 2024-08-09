<?php

namespace Adept\Document\HTML\Elements\Form\DropDown;

defined('_ADEPT_INIT') or die();

use Adept\Application;

class Country extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);
		$app = Application::getInstance();

		$countries = $app->db->getObjects(
			'SELECT `country`, `iso2` FROM `location_country` ORDER BY `country` ASC',
			[]
		);

		for ($c = 0; $c < count($countries); $c++) {
			$this->values[$countries[$c]->iso2] = $countries[$c]->country;
		}
	}
}
