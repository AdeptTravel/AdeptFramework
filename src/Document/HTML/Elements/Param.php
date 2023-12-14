<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Param extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'param';

	// Element Specific Attributes
	public string $name;
	public string $value;

}