<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Iframe extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'iframe';

	// Element Specific Attributes
	public int    $height;
	public string $name;
	public string $sandbox;
	public string $src;
	public string $srcdoc;
	public int    $width;

}