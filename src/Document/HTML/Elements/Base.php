<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Base extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'base';

	// Element Specific Attributes
	public string $href;
	public string $target;

}