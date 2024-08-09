<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Datalist extends \Adept\Abstract\Document\HTML\Element
{
	protected string $tag = 'datalist';

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
