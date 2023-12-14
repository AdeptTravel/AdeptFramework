<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Link extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'link';

	// Element Specific Attributes
	public string $crossorigin;
	public string $href;
	public string $hreflang;
	public string $media;
	public string $rel;
	public string $sizes;

}