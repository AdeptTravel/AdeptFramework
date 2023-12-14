<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Details extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'details';

	// Element Specific Attributes
	public bool   $open;

}