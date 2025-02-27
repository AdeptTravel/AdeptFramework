<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Route;

use Adept\Application;

defined('_ADEPT_INIT') or die();

class Area extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'Area';

		parent::__construct($attr, []);

		if (isset($attr['fromFS']) && $attr['fromFS']) {
			$dirs = array_merge(
				glob(FS_CORE_COMPONENT . '*/*/*', GLOB_ONLYDIR),
				glob(FS_SITE_COMPONENT . '*/*/*', GLOB_ONLYDIR)
			);

			for ($i = 0; $i < count($dirs); $i++) {
				$parts = explode('/', substr($dirs[$i], 1));
				$area = end($parts);

				if (!in_array($area, $this->values)) {
					$this->values[] = $area;
				}
			}

			asort($this->values);
		} else {
			$this->values = Arrays::ValueToArray(['Admin', 'Global', 'Public']);
		}
	}
}
