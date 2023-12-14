<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Th extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'th';

	// Element Specific Attributes
	public int    $colspan;
	public string $headers;
	public int    $rowspan;
	public string $scope;

}