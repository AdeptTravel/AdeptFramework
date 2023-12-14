<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Area extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'area';

	// Element Specific Attributes
	public string $alt;
	public string $coords;
	public string $download;
	public string $href;
	public string $hreflang;
	public string $media;
	public string $rel;
	public string $shape;
	public string $target;

}