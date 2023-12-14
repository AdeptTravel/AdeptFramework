<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Legend;

class Fieldset extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'fieldset';

	// Element Specific Attributes
	public bool   $disabled;
	public string $form;
	public string $name;
	public string $legend;

	public function getBuffer(): string
	{
		if (!empty($this->legend)) {
			$this->children[] = new Legend(['text' => $this->legend]);
		}

		return parent::getBuffer();
	}
}
