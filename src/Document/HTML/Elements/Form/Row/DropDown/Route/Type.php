<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Route;

use Adept\Application;

defined('_ADEPT_INIT') or die();

class Type extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Component';

		parent::__construct($attr, []);

		if (isset($attr['fromFS']) && $attr['fromFS']) {
			$dirs = array_merge(
				glob(FS_CORE_COMPONENT . '*', GLOB_ONLYDIR),
				glob(FS_SITE_COMPONENT . '*', GLOB_ONLYDIR)
			);

			for ($i = 0; $i < count($dirs); $i++) {
				$type = substr($dirs[$i], strrpos($dirs[$i], '/') + 1);

				if (!in_array($type, $this->values)) {
					$this->values[$type] = $type;
				}
			}

			asort($this->values);
		} else {
			$this->values = Arrays::ValueToArray([
				'BI',
				'CMS',
				'CRM',
				'Core',
				'ERP',
				'Shop',
				'System'
			]);
		}
	}
}
