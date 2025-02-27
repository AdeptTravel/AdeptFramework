<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Content;

defined('_ADEPT_INIT') or die();

class Tag  extends \Adept\Document\HTML\Elements\Form\DropDown
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
			$attr['placeholder'] = 'Tag';
		}

		$table = new \Adept\Data\Table\Content\Category();
		$data = $table->getData(true);

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
