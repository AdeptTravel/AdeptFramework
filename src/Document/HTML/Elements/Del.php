<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Del extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'del';

	// Element Specific Attributes
	public string $cite;
	public string $datetime;

}