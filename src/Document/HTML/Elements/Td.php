<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Td extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'td';

	// Element Specific Attributes
	public int    $colspan;
	public string $headers;
	public int    $rowspan;

}