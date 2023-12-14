<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Q extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'q';

	// Element Specific Attributes
	public string $cite;

}