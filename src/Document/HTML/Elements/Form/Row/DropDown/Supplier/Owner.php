<?php

namespace Adept\Document\HTML\Elements\Select\Supplier;

defined('_ADEPT_INIT') or die();

class Owner extends \Adept\Document\HTML\Elements\Select
{
	protected \Adept\Application\Database $db;

	public function __construct(array $attr = [])
	{
		$this->db = $db;
		$this->label = 'Parent';

		parent::__construct($attr, []);

		$data = $this->db->getObjects(
			'SELECT `id`, `title` FROM `supplier` WHERE `owner` = 0 ORDER BY `title` ASC',
			[]
		);

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]->id] = $data[$i]->title;
		}
	}
}
