<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Ins extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'ins';

	// Element Specific Attributes
	public string $cite;
	public string $datetime;

}