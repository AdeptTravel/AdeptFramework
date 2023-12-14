<?php

namespace Adept\Abstract\Document\HTML\Body;

defined('_ADEPT_INIT') or die();

//use \Adept\Application\Database;
use \Adept\Document\HTML\Attributes;
use \Adept\Document\HTML\Head;
//use \Adept\Application\Session;

abstract class Element
{


  public array $children = [];
  public bool $close = true;
  public string $tag;
  public string $value = '';

  // Global html attributes
  public string $accesskey;
  // Replaced with $css array
  //public string $class;
  public array $css = [];
  public bool $contenteditable;
  public string $dir;
  public bool $draggable;
  public bool $hidden;
  public string $id;
  public string $lang;
  public string $name;
  public bool $spellcheck;
  public int $tabindex;
  public string $title;
  public bool $translate;

  // Element specific attributes
  public string $accept;
  //public string $accept_charset;
  public string $alt;
  public string $action;
  public bool $autocomplete;
  public bool $autofocus;
  public bool $checked;
  public int $cols;
  public string $dirname;
  public bool $disabled;
  public string $enctype;
  public string $form;
  public string $formaction;
  public string $formenctype;
  public string $formmethod;
  public string $formnovalidate;
  public string $formtarget;
  public string $height;
  public string $indeterminate;
  public int $max;
  public int $maxlength;
  public string $method;
  public int $min;
  public bool $multiple;
  public bool $novalidate;
  public string $pattern;
  public string $placeholder;
  public bool $readonly;
  public string $rel;
  public bool $required;
  public int $rows;
  public string $size;
  public string $src;
  public string $step;
  public string $target;
  public string $width;
  public string $wrap;

  public function __construct(string $value, array $attr = [])
  {
    $this->value = $value;

    foreach ($attr as $k => $v) {
      $this->$k = $v;
    }
  }

  function getBuffer(): string
  {
    $html  = "<$this->tag" . $this->getAttr();

    if (!empty($this->value) && !$this->close) {
      $html .= 'value="' . $this->value . '"';
    }

    $html .= ">";

    if ($this->close) {
      if (!empty($this->value)) {
        $html .= $this->value;
      }

      $html .= "</$this->tag>";
    }

    return $html;
  }

  /**
   * Get a formatted list of element attribures
   *
   * @param   string  Element type, ie. input, textarea, form, etc.
   * @param   array   Element parameters
   *
   * @return  string  Formatted string of elements attribs to be dropped into
   *                  the HTML tag.
   */
  protected function getAttr(): string
  {
    $html = '';

    // Note: Some attributes are not set here such as class, data-*, id, and
    //       style.  These are generated elsewhere.
    //
    // name    - Attribute name
    // type    - The value of the attribute
    //           Accepted values:
    //           bool        Displays attribute with a true/false value.  Example <input draggable="true">
    //           attrib      Displays the just the attribute if true.  Example  <input hidden>
    //           int         Displays the attribute as an int.  Example <input tabindex="1">
    //           on-off      Displays the attribute with an on/off value.  Example <input autocomplete="on">
    //           string      Displays the attribute with a string.  Example <input placeholder="This is a place holder.">
    //
    // element - Type of element such as input, select, textarea, etc.
    // for     - Filter based on the input tag, if the input 'type' attribute
    $attributes = [

      // Global html attributes
      ['name' => 'accesskey',       'type' => 'string'],
      //['name' => 'class',           'type' => 'string'],
      ['name' => 'contenteditable', 'type' => 'bool'],
      ['name' => 'dir',             'type' => 'string'],
      ['name' => 'draggable',       'type' => 'bool'],
      ['name' => 'hidden',          'type' => 'attrib'],
      ['name' => 'id',              'type' => 'string'],
      ['name' => 'lang',            'type' => 'string'],
      ['name' => 'name',            'type' => 'string'],
      ['name' => 'spellcheck',      'type' => 'bool'],
      ['name' => 'tabindex',        'type' => 'int'],
      ['name' => 'title',           'type' => 'string'],
      ['name' => 'translate',       'type' => 'yes-no'],

      // Element specific attributes
      ['name' => 'accept',          'type' => 'string'],
      ['name' => 'accept-charset',  'type' => 'string'],
      ['name' => 'alt',             'type' => 'string'],
      ['name' => 'action',          'type' => 'string'],
      ['name' => 'autocomplete',    'type' => 'on-off'],
      ['name' => 'autofocus',       'type' => 'attrib'],
      ['name' => 'checked',         'type' => 'attrib'],
      ['name' => 'cols',            'type' => 'int'],
      ['name' => 'dirname',         'type' => 'string'],
      ['name' => 'disabled',        'type' => 'attrib'],
      ['name' => 'enctype',         'type' => 'string'],
      ['name' => 'form',            'type' => 'string'],
      ['name' => 'formaction',      'type' => 'string'],
      ['name' => 'formenctype',     'type' => 'string'],
      ['name' => 'formmethod',      'type' => 'string'],
      ['name' => 'formnovalidate',  'type' => 'attrib'],
      ['name' => 'formtarget',      'type' => 'string'],
      ['name' => 'height',          'type' => 'string'],
      ['name' => 'indeterminate',   'type' => 'string'],
      ['name' => 'max',             'type' => 'int'],
      ['name' => 'maxlength',       'type' => 'int'],
      ['name' => 'method',          'type' => 'string'],
      ['name' => 'min',             'type' => 'int'],
      ['name' => 'multiple',        'type' => 'attrib'],
      ['name' => 'novalidate',      'type' => 'attrib'],
      ['name' => 'pattern',         'type' => 'string'],
      ['name' => 'placeholder',     'type' => 'string'],
      ['name' => 'readonly',        'type' => 'attrib'],
      ['name' => 'rel',             'type' => 'string'],
      ['name' => 'required',        'type' => 'attrib'],
      ['name' => 'rows',            'type' => 'int'],
      ['name' => 'size',            'type' => 'string'],
      ['name' => 'src',             'type' => 'string'],
      ['name' => 'step',            'type' => 'string'],
      ['name' => 'target',          'type' => 'string'],
      ['name' => 'width',           'type' => 'string'],
      ['name' => 'wrap',            'type' => 'string']
    ];

    if (!empty($this->css)) {
      $html .= ' class="' . implode(' ', $this->css) . '"';
    }

    foreach ($attributes as $attribute) {

      $key = $attribute['name'];

      if (!empty($this->$key)) {

        $html .= ' ' . $key;

        if ($attribute['type'] != 'attrib') {

          switch ($attribute['type']) {

            case 'bool':
              $html .= '="' . (($attr[$key]) ? 'true' : 'false') . '"';
              break;

            case 'string':
              $html .= '="' . (string)$attr[$key] . '"';
              break;

            case 'int':
              $html .= '="' . (int)$attr[$key] . '"';
              break;

            case 'yes-no':
              $html .= '="' . (($attr[$key]) ? 'on' : 'off') . '"';
              break;

            case 'none':
              $html .= '="' . (($attr[$key]) ? '' : 'none') . '"';
              break;
          }
        }
      }
    }

    return ' ' . trim($html);
  }
}
