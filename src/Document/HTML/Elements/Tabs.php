<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

use \Adept\Application;

class Tabs extends \Adept\Abstract\Document\HTML\Element
{
  protected string $tag = 'div';

  public function __construct(array $attr = [], array $children = [])
  {
    parent::__construct($attr);

    $app = Application::getInstance();

    $app->html->head->css->addAsset('Core/Tabs');
    $app->html->head->javascript->addAsset('Core/Tabs');

    $this->css[] = 'tabs';
  }

  public function getBuffer(): string
  {
    $nav = new Div(['css' => ['tabnav']]);

    for ($i = 0; $i < count($this->children); $i++) {
      $nav->children[] = new Button([
        'text' => $this->children[$i]->title
      ]);
    }

    array_unshift($this->children, $nav);

    return parent::getBuffer();
  }
}
