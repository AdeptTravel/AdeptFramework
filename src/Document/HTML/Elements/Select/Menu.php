<?php

namespace Adept\Document\HTML\Elements\Select;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Menu extends \Adept\Document\HTML\Elements\Select
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{
		$this->label = '-- Menu --';

		parent::__construct($attr, []);

		$items = new \Adept\Data\Table\Menu($db);
		$data = $items->getData();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
