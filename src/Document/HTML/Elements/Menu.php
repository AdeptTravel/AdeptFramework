<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Menu extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'menu';

	// Element Specific Attributes
	public string $type;

}