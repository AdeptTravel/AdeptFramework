<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Colgroup extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'colgroup';

	// Element Specific Attributes
	public int    $span;

}