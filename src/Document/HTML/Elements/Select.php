<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Select extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'select';

	// Element Specific Attributes
	public string $autocomplete;
	public bool   $autofocus;
	public bool   $disabled;
	public string $form;
	public bool   $multiple;
	public string $name;
	public bool   $required;
	public int    $size;


	public string $label = '';
	public string $value = '';
	public array $values = [];

	public function getBuffer(): string
	{
		$this->children[] = new Option([
			'value' => '',
			'text' => $this->label,
			'selected' => (empty($this->value))
		]);

		foreach ($this->values as $k => $v) {

			$option = new Option([
				'value' => $k,
				'text' => $v
			]);

			if ($v == $this->value) {
				$option->selected = true;
			}

			$this->children[] = $option;
		}

		return parent::getBuffer();
	}
}
