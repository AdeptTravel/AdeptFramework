<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Embed extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'embed';

	// Element Specific Attributes
	public int    $height;
	public string $src;
	public string $type;
	public int    $width;

}