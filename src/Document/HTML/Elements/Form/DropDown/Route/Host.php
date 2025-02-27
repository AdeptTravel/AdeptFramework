<?php

namespace Adept\Document\HTML\Elements\Form\DropDown\Route;

use \Adept\Application;

defined('_ADEPT_INIT') or die();

class Host extends \Adept\Document\HTML\Elements\Form\DropDown
{
	/**
	 * Undocumented function
	 *
	 * @param  Database $db
	 * @param  array                       $attr
	 */
	public function __construct(array $attr = [])
	{
		parent::__construct($attr);

		if (empty($attr['placeholder'])) {
			$attr['placeholder'] = 'Host';
		}

		$data = Application::getInstance()->conf->site->host;

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]] = $data[$i];
		}
	}
}
