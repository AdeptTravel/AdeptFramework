<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Label extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'label';

	// Element Specific Attributes
	public string $for;
	public string $form;

}