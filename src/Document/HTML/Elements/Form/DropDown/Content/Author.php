<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Content;

defined('_ADEPT_INIT') or die();

class Author extends \Adept\Document\HTML\Elements\Form\DropDown
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
		parent::__construct($attr);

		$table = new \Adept\Data\Table\User();

		$data = $table->getData();

		for ($i = 0; $i < count($data); $i++) {

			$this->values[$data[$i]->id] = $data[$i]->firstName . ' ';

			if (!empty($data[$i]->middleName)) {
				$this->values[$data[$i]->id] .= $data[$i]->middleName . ' ';
			}

			$this->values[$data[$i]->id] .= $data[$i]->lastName . ' ';
		}
	}
}
