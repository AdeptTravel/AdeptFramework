<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Menu;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Item extends \Adept\Document\HTML\Elements\Form\DropDown
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{
		// This has to be called last
		parent::__construct($attr);

		$items = new \Adept\Data\Items\Menu\Item();
		$data = $items->getList();

		for (
			$i = 0;
			$i < count($data);
			$i++
		) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
