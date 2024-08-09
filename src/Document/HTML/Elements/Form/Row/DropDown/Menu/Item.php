<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Menu;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Item extends \Adept\Document\HTML\Elements\Form\Row\DropDown
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
		$this->label = 'Menu Item';

		parent::__construct($attr, []);

		$items = new \Adept\Data\Items\Menu\Item();
		$data = $items->getList();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
