<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Optgroup extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'optgroup';

	// Element Specific Attributes
	public bool   $disabled;
	public string $label;

}