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

		$table = new \Adept\Data\Table\Menu\Item();
		$data  = $table->getData();

		for ($i = 0; $i < count($data); $i++) {
			$title = $data[$i]->title;

			if ($data[$i]->level > 0) {
				$title = str_repeat('&nbsp', $data[$i]->level) . '- ' . $title;
			}
			$this->values[$data[$i]->id] = $title;
		}
	}
}
