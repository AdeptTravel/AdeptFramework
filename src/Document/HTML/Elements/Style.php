<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Style extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'style';

	// Element Specific Attributes
	public string $media;
	public bool   $scoped;
	public string $type;

}