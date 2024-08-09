<?php

namespace Adept\Document\HTML\Elements\Select\Filter\Route;

defined('_ADEPT_INIT') or die();

class Category extends \Adept\Document\HTML\Elements\Select
{
	public function __construct(array $attr = [])
	{
		$this->label = 'Category';

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
