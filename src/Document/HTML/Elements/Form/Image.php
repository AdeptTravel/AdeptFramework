<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use \Adept\Document\HTML\Elements\A;
use \Adept\Document\HTML\Elements\Button;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Li;
use \Adept\Document\HTML\Elements\Span;
use \Adept\Document\HTML\Elements\Ul;

class Image extends \Adept\Document\HTML\Elements\Div
{
  // Form element attributes
  public string $name;
  public bool   $required = false;

  public bool   $autofocus;
  public bool   $disabled;
  public string $form;
  public string $value = '';

  // Input specific attributes
  public string $accept;
  public string $alt;
  public string $dirname;
  public string $formaction;
  public string $formenctype;
  public string $formmethod;
  public bool   $formnovalidate;
  public string $formtarget;
  public int    $height;
  public string $max;
  public int    $maxlength;
  public string $min;
  public int    $minlength;
  public bool   $multiple;
  public string $pattern;
  public string $placeholder;
  public bool   $readonly = true;
  public int    $size;
  public string $src;
  public string $step;
  public string $type;
  public string $usemap;
  public int    $width;
  // Custom param
  public bool   $allowEmpty = true;
  public array  $optionShowOn = [];
  public array  $optionHideOn = [];
  // Duplicate, here for reference only
  //public bool   $required; 
  //public string $label = '';

  public function getBuffer(): string
  {
    $app = Application::getInstance();
    $app->html->head->css->addFile('form.image.css');
    $app->html->head->javascript->addFile('form.image.js');

    $path  = '';
    $title = '';

    if (!empty($this->value)) {
      $data  = new \Adept\Data\Item\Media\Image((int)$this->value);
      $path  = $data->getRelPath(150, 1050);
      $title = $data->title;
    }

    $this->children[] =  new \Adept\Document\HTML\Elements\Img([
      'src' => $path,
    ]);

    $this->children[] = new Div([
      'css' => ['title'],
      'text' => $title
    ]);

    $this->children[] = new Div([
      'css' => ['controls']
    ]);

    end($this->children)->children[] = new Button([
      'css' => ['select'],
      'title' => 'Select',
      'text' => 'Select'
    ]);

    end($this->children)->children[] = new Button([
      'css' => ['clear'],
      'title' => 'clear',
      'text' => 'Clear'
    ]);

    return parent::getBuffer();
  }
}
