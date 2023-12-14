<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Ol extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'ol';

	// Element Specific Attributes
	public bool   $reversed;
	public int    $start;

}