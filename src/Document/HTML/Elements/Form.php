<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Input\Hidden;

class Form extends \Adept\Abstract\Document\HTML\Element
{
	protected string $tag = 'form';
	protected bool $edit = false;

	// Element Specific Attributes
	public string $acceptcharset;
	public string $action;
	public bool   $autocomplete;
	public string $enctype;
	public string $method;
	public string $name;
	public bool   $novalidate;
	public array  $tabs = [];
	public string $target;

	public function __construct(array $attr = [], array $children = [])
	{
		parent::__construct($attr, $children);

		$_SESSION['form_token'] = md5('adept_' . microtime());
	}

	public function getBuffer(): string
	{
		if (!empty($this->method) && strtolower($this->method) != 'get') {
			$this->children[] = new Hidden(
				[
					'name' => 'form_token',
					'value' => $_SESSION['form_token']
				]
			);
		}

		return parent::getBuffer();
	}
}
