<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Option extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'option';

	// Element Specific Attributes
	public bool   $disabled;
	public string $label;
	public bool   $selected;
	public string $value;

	function getBuffer(): string
	{
		$html  = "<option" . $this->getAttr();

		if (empty($this->value)) {
			$html .= ' value=""';
		}

		$html .= ">";

		if (!empty($this->text)) {
			$html .= $this->text;
		}

		$html .= "</option>";


		return $html;
	}
}
