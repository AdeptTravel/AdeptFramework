<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Blockquote extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'blockquote';

	// Element Specific Attributes
	public string $cite;

}