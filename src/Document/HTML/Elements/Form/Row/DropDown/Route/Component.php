<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Route;

defined('_ADEPT_INIT') or die();

class Component extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Component';

		parent::__construct($attr, []);

		$data = [];
		$dirs = array_merge(
			glob(FS_CORE_COMPONENT . '*', GLOB_ONLYDIR),
			glob(FS_SITE_COMPONENT . '*', GLOB_ONLYDIR)
		);

		for ($i = 0; $i < count($dirs); $i++) {
			$val = substr($dirs[$i], strrpos($dirs[$i], '/') + 1);

			if (!in_array($val, $data)) {
				$data[] = $val;
			}
		}

		sort($data);

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]] = $data[$i];
		}
	}
}
