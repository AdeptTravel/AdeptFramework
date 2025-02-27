<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class TextArea extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'textarea';

	// Element Specific Attributes
	public bool
		$autocomplete;
	public bool   $autofocus;
	public int    $cols;
	public string $dirname;
	public bool   $disabled;
	public string $form;
	public int    $maxlength;
	public int    $minlength;
	public string $name;
	public string $placeholder;
	public bool   $readonly;
	public bool   $required;
	public int    $rows;
	public string $wrap;
}
