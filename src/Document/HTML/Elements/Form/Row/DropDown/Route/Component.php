<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Route;

use Adept\Application;

defined('_ADEPT_INIT') or die();

class Component extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Component';

		parent::__construct($attr, []);

		Application::getInstance()->html->head->javascript->addAsset('Core/Form/Conditional');

		$dirs = array_merge(
			glob(FS_CORE_COMPONENT . '*/*', GLOB_ONLYDIR),
			glob(
				FS_SITE_COMPONENT . '*/*',
				GLOB_ONLYDIR
			)
		);

		for ($i = 0; $i < count($dirs); $i++) {

			$parts = explode('/', substr($dirs[$i], 1));
			$type = $parts[count($parts) - 2];
			$component = $parts[count($parts) - 1];

			if (!in_array($component, $this->values)) {
				$this->values[$component] = $component;
			}

			$showon = 'type=' . $type;

			if (!isset($this->conditions[$component]) || !in_array($showon, $this->conditions[$component])) {
				$this->conditions[$component][] = $showon;
			}
		}

		asort($this->values);
	}
}
