<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Route extends \Adept\Document\HTML\Elements\Form\DropDown
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		$items = new \Adept\Data\Items\Route();
		$data = $items->getList();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->route;
		}
	}
}
