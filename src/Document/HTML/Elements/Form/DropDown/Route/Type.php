<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Route;

use Adept\Helper\Arrays;

defined('_ADEPT_INIT') or die();

class Type extends \Adept\Document\HTML\Elements\Form\DropDown
{
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		$this->emptyDisplay = '-- ' . $this->placeholder . ' --';

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
