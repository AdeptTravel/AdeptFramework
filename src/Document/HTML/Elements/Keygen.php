<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Keygen extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'keygen';

	// Element Specific Attributes
	public bool   $disabled;
	public string $form;
	public string $name;

}