<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use \Adept\Document\HTML\Elements\Button;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Input\Hidden;

class Image extends \Adept\Document\HTML\Elements\Div
{
  public array $attr;

  public string $value;

  public function __construct(array $attr = [], array $children = [], bool $validate = false, bool $status = true)
  {
    $attrs = [];

    if (!empty($attr['css'])) {
      $attrs['css'] = $attr['css'];
    }

    $attrs['css'][] = 'image';

    parent::__construct($attrs, $children);

    $this->attr = $attr;
  }

  public function getBuffer(): string
  {
    $app = Application::getInstance();

    $app->html->head->css->addAsset('Core/Modal');
    $app->html->head->css->addAsset('Core/Form/Image');
    $app->html->head->javascript->addAsset('Core/Modal');
    $app->html->head->javascript->addAsset('Core/Form/Image');

    $path  = '';
    $data  = new \Adept\Data\Item\Media\Image();

    if (!empty($this->value)) {
      $data->loadFromId((int)$this->value);
      $path = $data->getRelPath(360, 203);
    }

    if (empty($path)) {
      $this->css[] = 'empty';
    }


    $this->children[] = new Hidden([
      'value' => $data->id,
      'name' => (!empty($this->attr['name'])) ? $this->attr['name'] : ''
    ]);

    $this->children[] = new Div([
      'css' => ['placeholder']
    ]);

    $this->children[] = new \Adept\Document\HTML\Elements\Img([
      'src' => $path
    ]);

    $this->children[] = new div([
      'css' => ['title'],
      'text' => (isset($data->title)) ? $data->title : ''
    ]);

    $controls = new Div([
      'css' => ['controls']
    ]);

    $controls->children[] = new Button([
      'css' => ['select'],
      'title' => 'Select',
      'text' => 'Select'
    ]);

    $controls->children[] = new Button([
      'css' => ['clear'],
      'title' => 'clear',
      'text' => 'Clear'
    ]);

    $this->children[] = $controls;

    return parent::getBuffer();
  }
}
