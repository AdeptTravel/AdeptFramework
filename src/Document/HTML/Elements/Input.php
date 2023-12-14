<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Input extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'input';
	protected bool $close = false;

	// Element Specific Attributes
	public string $accept;
	public string $alt;
	public string $autocomplete;
	public bool   $autofocus;
	public bool   $checked;
	public string $dirname;
	public bool   $disabled;
	public string $form;
	public string $formaction;
	public string $formenctype;
	public string $formmethod;
	public bool   $formnovalidate;
	public string $formtarget;
	public int    $height;
	public string $list;
	public string $max;
	public int    $maxlength;
	public string $min;
	public int    $minlength;
	public bool   $multiple;
	public string $name;
	public string $pattern;
	public string $placeholder;
	public bool   $readonly;
	public bool   $required;
	public int    $size;
	public string $src;
	public string $step;
	public string $type;
	public string $usemap;
	public string $value;
	public int    $width;

	function getBuffer(): string
	{
		$html = parent::getBuffer();

		if (!empty($this->list)) {
			$html .= '<datalist id="' . $this->list . '"></datalist>';
		}

		return $html;
	}
}
