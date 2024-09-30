<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Route;

defined('_ADEPT_INIT') or die();

class View extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'View';

		parent::__construct($attr, []);

		$data = [];

		$vals = array_merge(
			glob(FS_CORE_COMPONENT . '*/*/*.php'),
			glob(FS_SITE_COMPONENT . '*/*/*.php'),
			glob(FS_CORE_COMPONENT . '*/HTML/Template/*'),
			glob(FS_SITE_COMPONENT . '*/HTML/Template/*')
		);
		//die('<pre>' . print_r($vals, true));
		$conditions = [];

		for ($i = 0; $i < count($vals); $i++) {
			$parts = explode('/', substr($vals[$i], 1));

			$view = $parts[count($parts) - 1];
			$view = substr($parts[count($parts) - 1], 0, -4);
			$component = $parts[count($parts) - 3];

			if ($component == 'HTML') {
				$component = $parts[count($parts) - 4];
			}


			if (!in_array($view, $data)) {
				$data[] = $view;
			}

			$showon = 'component=' . $component;

			if (!isset($conditions[$view]) || !in_array($showon, $conditions[$view])) {
				$conditions[$view][] = $showon;
			}
		}

		$this->conditions = $conditions;

		sort($data);

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]] = $data[$i];
		}
	}
}
