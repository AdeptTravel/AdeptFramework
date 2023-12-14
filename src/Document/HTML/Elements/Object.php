<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Object extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'object';

	// Element Specific Attributes
	public string $data;
	public string $form;
	public int    $height;
	public string $name;
	public string $type;
	public string $usemap;
	public int    $width;

}