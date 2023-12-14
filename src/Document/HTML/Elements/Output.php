<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Output extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'output';

	// Element Specific Attributes
	public string $for;
	public string $form;
	public string $name;

}