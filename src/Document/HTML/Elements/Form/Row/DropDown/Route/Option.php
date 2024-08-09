<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Route;

defined('_ADEPT_INIT') or die();

class Option extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Option';

		parent::__construct($attr, []);

		$data = [];

		$vals = array_merge(
			glob(FS_CORE_COMPONENT . '*/*/*.php'),
			glob(FS_SITE_COMPONENT . '*/*/*.php'),
			glob(FS_CORE_COMPONENT . '*/HTML/Template/*'),
			glob(FS_SITE_COMPONENT . '*/HTML/Template/*')
		);

		$conditions = [];

		for ($i = 0; $i < count($vals); $i++) {
			$parts = explode('/', substr($vals[$i], 1));

			$option = $parts[count($parts) - 1] = substr($parts[count($parts) - 1], 0, -4);
			$component = $parts[count($parts) - 3];

			if ($component == 'HTML') {
				$component = $parts[count($parts) - 4];
			}


			if (!in_array($option, $data)) {
				$data[] = $option;
			}

			if (array_key_exists($option, $conditions)) {
				if (!in_array($component, $conditions[$option])) {
					$conditions[$option][] = $component;
				}
			} else {
				$conditions[$option] = [$component];
			}
		}

		$this->conditions = $conditions;

		sort($data);

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]] = $data[$i];
		}
	}
}
