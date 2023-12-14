<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Meta extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'meta';

	// Element Specific Attributes
	public string $charset;
	public string $content;
	public string $httpequiv;
	public string $name;
	// For Schema.org
	public string $property;
}
