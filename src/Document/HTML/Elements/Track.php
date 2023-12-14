<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Track extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'track';

	// Element Specific Attributes
	public bool   $default;
	public string $kind;
	public string $label;
	public string $src;
	public string $srclang;

}