<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Source extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'source';

	// Element Specific Attributes
	public string $media;
	public string $sizes;
	public string $src;
	public string $srcset;
	public string $type;

}