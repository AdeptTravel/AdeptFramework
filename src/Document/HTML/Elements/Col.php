<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Col extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'col';

	// Element Specific Attributes
	public int    $span;

}