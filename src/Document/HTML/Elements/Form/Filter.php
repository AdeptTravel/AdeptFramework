<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Document\HTML\Elements\P;

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

    $this->children[] = new P(['text' => 'Filter']);
  }
}
