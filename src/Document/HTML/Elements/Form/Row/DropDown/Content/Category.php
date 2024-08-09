<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Content;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Category extends \Adept\Document\HTML\Elements\Form\Row\DropDown
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

		parent::__construct($attr);

		$items = new \Adept\Data\Items\Content\Category();

		$data = $items->getList();

		for ($i = 0; $i < count($data); $i++) {

			$this->values[$data[$i]->id] = str_repeat('-', $data[$i]->level);

			if ($data[$i]->level > 0) {
				$this->values[$data[$i]->id] .= ' ';
			}

			$this->values[$data[$i]->id] .= $data[$i]->title;
		}
	}
}
