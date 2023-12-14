<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Meter extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'meter';

	// Element Specific Attributes
	public string $form;
	public int    $high;
	public int    $low;
	public string $max;
	public string $min;
	public int    $optimum;
	public string $value;

}