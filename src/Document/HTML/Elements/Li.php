<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Li extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'li';

	// Element Specific Attributes
	public string $value;

}