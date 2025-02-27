<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Content;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Tag extends \Adept\Document\HTML\Elements\Form\Row\DropDown
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
		$this->label = 'Parent Tag';

		parent::__construct($attr);

		$table = new \Adept\Data\Table\Content;
		$table->type = 'Tag';

		$data = $table->getData(true);

		for ($i = 0; $i < count($data); $i++) {

			$this->values[$data[$i]->id] = str_repeat('-', $data[$i]->level);

			if ($data[$i]->level > 0) {
				$this->values[$data[$i]->id] .= ' ';
			}

			$this->values[$data[$i]->id] .= $data[$i]->title;
		}
	}
}
