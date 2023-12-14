<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Map extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'map';

	// Element Specific Attributes
	public string $name;

}