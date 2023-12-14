<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Progress extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'progress';

	// Element Specific Attributes
	public string $form;
	public string $max;
	public string $value;

}