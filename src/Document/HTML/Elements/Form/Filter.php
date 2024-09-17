<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Fieldset;
use Adept\Document\HTML\Elements\Legend;

class Filter extends \Adept\Document\HTML\Elements\Form
{
  public array $css = ['filter'];
  public string $method = 'GET';

  public function __construct(array $attr = [], array $children = [])
  {
    parent::__construct($attr, $children);

    $app = Application::getInstance();
    $app->html->head->css->addFile('form.filter.css');
    $app->html->head->javascript->addFile('form.filter.js');
  }

  public function getBuffer(): string
  {
    $fieldset = new Fieldset();
    $fieldset->children[] = new Legend(['text' => 'Filter']);

    for ($i = 0; $i < count($this->children); $i++) {
      $fieldset->children[] = $this->children[$i];
    }

    $this->children = [$fieldset];

    return parent::getBuffer();
  }
}
