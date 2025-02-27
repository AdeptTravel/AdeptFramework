<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Route;

use Adept\Application;

defined('_ADEPT_INIT') or die();

class View extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		Application::getInstance()->html->head->javascript->addAsset('Core/Form/Conditional');

		$this->emptyDisplay = '-- ' . $this->placeholder . ' --';

		$dirs = array_merge(
			glob(FS_CORE_COMPONENT . '*/*/*/*/*.php'),
			glob(FS_SITE_COMPONENT . '*/*/*/*/*.php'),
			glob(FS_CORE_COMPONENT . '*/*/HTML/Template/*'),
			glob(FS_SITE_COMPONENT . '*/*/HTML/Template/*')
		);

		for ($i = 0; $i < count($dirs); $i++) {

			$parts = explode('/', substr($dirs[$i], 1));
			$view = $parts[count($parts) - 1] = substr($parts[count($parts) - 1], 0, -4);
			$component = $parts[count($parts) - 4];

			if (!in_array($view, $this->values)) {
				$this->values[$view] = $view;
			}

			$showon = 'component=' . $component;

			if (!isset($this->considitons[$view]) || !in_array($showon, $this->conditions[$view])) {
				$this->conditions[$view][] = $showon;
			}
		}

		asort($this->values);
	}
}
