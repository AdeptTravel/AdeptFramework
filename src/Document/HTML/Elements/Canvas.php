<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Canvas extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'canvas';

	// Element Specific Attributes
	public int    $height;
	public int    $width;

}