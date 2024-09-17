<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Content;

defined('_ADEPT_INIT') or die();

class Category extends \Adept\Document\HTML\Elements\Form\DropDown
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{
		// Parent construct has to be called after everything else
		parent::__construct($attr);

		if (empty($attr['placeholder'])) {
			$attr['placeholder'] = 'Category';
		}

		$items = new \Adept\Data\Table\Content\Category();
		$data = $items->getList();

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
