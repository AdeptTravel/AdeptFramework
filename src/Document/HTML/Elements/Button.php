<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Button extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'button';

	// Element Specific Attributes
	public bool   $autofocus;
	public bool   $disabled;
	public string $form;
	public string $formaction;
	public string $formenctype;
	public string $formmethod;
	public bool   $formnovalidate;
	public string $formtarget;
	public string $name;
	public string $type;
	public string $value;

}