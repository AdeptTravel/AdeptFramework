<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Route extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Route';

		parent::__construct($attr, []);

		$table = new \Adept\Data\Table\Route();
		$data  = $table->getData();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->route;
		}
	}
}
