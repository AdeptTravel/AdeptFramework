<?php

namespace Adept\Document\HTML\Elements\Select\Route;

use Adept\Application;

defined('_ADEPT_INIT') or die();

class Option extends \Adept\Abstract\Document\HTML\Element
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
	public array $values = [];

	public function __construct(array $attr = [])
	{
		$this->label = '- Option -';

		parent::__construct($attr, []);

		$data = [];

		$vals = array_merge(
			glob(FS_CORE_COMPONENT . '*/*/*.php'),
			glob(FS_SITE_COMPONENT . '*/*/*.php'),
			glob(FS_CORE_COMPONENT . '*/HTML/Template/*'),
			glob(FS_SITE_COMPONENT . '*/HTML/Template/*')
		);

		$conditions = [];

		for ($i = 0; $i < count($vals); $i++) {
			$parts = explode('/', substr($vals[$i], 1));

			$option = $parts[count($parts) - 1] = substr($parts[count($parts) - 1], 0, -4);
			$component = $parts[count($parts) - 3];

			if ($component == 'HTML') {
				$component = $parts[count($parts) - 4];
			}


			if (!in_array($option, $data)) {
				$data[] = $option;
			}

			if (array_key_exists($option, $conditions)) {
				if (!in_array($component, $conditions[$option])) {
					$conditions[$option][] = $component;
				}
			} else {
				$conditions[$option] = [$component];
			}
		}

		$this->conditions = $conditions;

		sort($data);

		for ($i = 0; $i < count($data); $i++) {
			$this->values[$data[$i]] = $data[$i];
		}
	}

	public function getBuffer(): string
	{

		Application::getInstance()->html->head->javascript->addAsset('Core/Form/Conditional');

		$this->children[] = new \Adept\Document\HTML\Elements\Option([
			'value' => '',
			'text' => $this->label,
			'selected' => (empty($this->value))
		]);

		foreach ($this->values as $k => $v) {
			$option = new \Adept\Document\HTML\Elements\Option([
				'value' => $k,
				'text' => $v
			]);

			if ($v == $this->value) {
				$option->selected = true;
			}

			if (array_key_exists($k, $this->conditions)) {
				for ($i = 0; $i < count($this->conditions[$k]); $i++) {
					$option->showOn[] = 'component=' . $this->conditions[$k][$i];
					//$option->addCondition('component', $conditions[$k][$i]);
				}
			}

			$this->children[] = $option;
		}

		return parent::getBuffer();
	}
}
