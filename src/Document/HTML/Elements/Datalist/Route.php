<?php

namespace Adept\Document\HTML\Elements\Datalist;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Route extends \Adept\Document\HTML\Elements\Datalist
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{
		parent::__construct($attr, []);

		$items = new \Adept\Data\Items\Route($db);
		$data = $items->getData();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->route;
		}
	}
}
