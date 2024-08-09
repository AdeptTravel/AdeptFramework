<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Route;

defined('_ADEPT_INIT') or die();

class Option extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		$data = [];

		$vals = array_merge(
			glob(FS_CORE_COMPONENT . '*/*/*.php'),
			glob(FS_SITE_COMPONENT . '*/*/*.php'),
			glob(FS_CORE_COMPONENT . '*/HTML/Template/*'),
			glob(FS_SITE_COMPONENT . '*/HTML/Template/*')
		);

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

			if (array_key_exists($option, $this->optionShowOn)) {
				if (!in_array($component, $this->optionShowOn[$option])) {
					$this->optionShowOn[$option][] = 'component=' . $component;
				}
			} else {
				$this->optionShowOn[$option] = ['component=' . $component];
			}
		}

		sort($data);

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]] = $data[$i];
		}
	}
}
