<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Route;

defined('_ADEPT_INIT') or die();

class Template extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		$this->emptyDisplay = '-- ' . $this->placeholder . ' --';

		$dirs = array_merge(
			glob(FS_CORE_TEMPLATE . '*', GLOB_ONLYDIR),
			glob(FS_SITE_TEMPLATE . '*', GLOB_ONLYDIR)
		);

		for ($i = 0; $i < count($dirs); $i++) {
			$template = substr($dirs[$i], strrpos($dirs[$i], '/') + 1);

			if (!in_array($template, $this->values)) {
				$this->values[$template] = $template;
			}
		}

		asort($this->values);
	}
}
