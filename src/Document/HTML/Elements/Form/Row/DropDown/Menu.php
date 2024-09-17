<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Menu extends \Adept\Document\HTML\Elements\Form\Row\DropDown
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
		$this->label = 'Menu';

		// Parent construct has to be called after everything else
		parent::__construct($attr);

		$table = new \Adept\Data\Table\Menu();
		$data  = $table->getData();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
