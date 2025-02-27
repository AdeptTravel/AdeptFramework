<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Route;

use Adept\Application;
use Adept\Helper\Arrays;

defined('_ADEPT_INIT') or die();

class Host extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
	public function __construct(array $attr = [])
	{
		// Placing this before the parent::__construct saves us having to check if
		// empty before populating
		$this->label = 'View';

		parent::__construct($attr, []);

		if (empty($attr['placeholder'])) {
			$attr['placeholder'] = '-- Host --';
		}

		$this->values = Arrays::ValueToArray(Application::getInstance()->conf->site->host);
	}
}
