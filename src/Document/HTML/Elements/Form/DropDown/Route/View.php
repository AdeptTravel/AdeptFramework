<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Route;

defined('_ADEPT_INIT') or die();

class View extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		$this->emptyDisplay = '-- ' . $this->placeholder . ' --';

		$data = [];

		$vals = array_merge(
			glob(FS_CORE_COMPONENT . '*/*/*.php'),
			glob(FS_SITE_COMPONENT . '*/*/*.php'),
			glob(FS_CORE_COMPONENT . '*/HTML/Template/*'),
			glob(FS_SITE_COMPONENT . '*/HTML/Template/*')
		);

		for ($i = 0; $i < count($vals); $i++) {
			$parts = explode('/', substr($vals[$i], 1));

			$view = $parts[count($parts) - 1] = substr($parts[count($parts) - 1], 0, -4);
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
