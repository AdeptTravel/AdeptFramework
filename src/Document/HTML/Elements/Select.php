<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Select extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'select';

	// Element Specific Attributes
	public bool
		$autocomplete;
	public bool   $autofocus;
	public bool   $disabled;
	public string $form;
	public bool   $multiple;
	public string $name;
	public bool   $required;
	public int    $size;

	public string $label = '';
	public string $value = '';
	public array  $values = [];

	// Custom param
	public bool   $allowEmpty = true;


	public function getBuffer(): string
	{

		if (!empty($this->label) && $this->allowEmpty == true) {
			$this->children[] = new Option([
				'value' => '',
				'text' => $this->label,
				'selected' => (empty($this->value))
			]);
		}

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
