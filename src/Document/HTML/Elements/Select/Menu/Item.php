<?php

namespace Adept\Document\HTML\Elements\Select\Menu;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Item extends \Adept\Document\HTML\Elements\Select
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{

		$this->label = '-- Menu Item --';

		parent::__construct($attr, []);

		$items = new \Adept\Data\Items\Menu\Item($db);
		$data = $items->getData();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
