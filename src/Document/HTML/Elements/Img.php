<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Img extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'img';

	// Element Specific Attributes
	public string $alt;
	public string $crossorigin;
	public int    $height;
	public bool   $ismap;
	public string $sizes;
	public string $src;
	public string $srcset;
	public string $usemap;
	public int    $width;

}