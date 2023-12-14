<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Time extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'time';

	// Element Specific Attributes
	public string $datetime;

}