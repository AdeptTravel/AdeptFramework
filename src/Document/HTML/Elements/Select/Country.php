<?php

namespace Adept\Document\HTML\Elements\Select;

defined('_ADEPT_INIT') or die();

class Country extends \Adept\Document\HTML\Elements\Select
{
	public function __construct(array $attr = [])
	{
		$this->label = 'Country';

		parent::__construct($attr, []);

		$countries = $this->app->db->getObjects(
			'SELECT `country`, `iso2` FROM `location_country` ORDER BY `country` ASC',
			[]
		);

		for ($c = 0; $c < count($countries); $c++) {
			$this->values[$countries[$c]->iso2] = $countries[$c]->country;
		}
	}
}
