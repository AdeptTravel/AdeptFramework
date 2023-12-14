<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class A extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'a';

	// Element Specific Attributes
	public string $download;
	public string $href;
	public string $hreflang;
	public string $media;
	public string $rel;
	public string $target;

}