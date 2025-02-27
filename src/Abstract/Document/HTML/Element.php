<?php

namespace Adept\Abstract\Document\HTML;

defined('_ADEPT_INIT') or die();

//use Attribute;
use Adept\Document\HTML;
use Adept\Document\HTML\Head;

abstract class Element
{
  /**
   * Undocumented variable
   *
   * @var bool
   */
  protected bool $close = true;

  /**
   * Undocumented variable
   *
   * @var string
   */
  protected string $tag = '';

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $children;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $html;

  public string $text;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $css = [];

  // Global Attributes
  public string $accesskey;
  public string $class;
  public bool   $contenteditable;
  public string $dir;
  public bool   $disabled;
  public bool   $draggable;
  public string $dropzone;
  public bool   $hidden;
  public string $id;
  public string $lang;
  public bool   $spellcheck;
  public string $style;
  public int    $tabindex;
  public string $title;
  public bool   $translate;

  public array $conditions = [];
  public array $showOn = [];
  public array $hideOn = [];

  public array $data = [];

  /**
   * Undocumented function
   *
   * @param  \Adept\Applicaiton   $app
   * @param  \Adept\Document\HTML $doc
   * @param  array                $attr
   * @param  array                $children
   */
  public function __construct(array $attr = [], array $children = [])
  {
    $this->children = $children;



    foreach ($attr as $k => $v) {

      if (property_exists($this, $k) && $v != null) {

        if ($k == 'value' && $v == 0) {
          $attr['value'] = "0";
        }

        $this->$k = $v;
      }
    }
  }

  public function getBuffer(): string
  {
    $html  = '<' . $this->tag;
    $html .= $this->renderAttr();

    if (!empty($this->hideOn)) {
      $html .= ' data-hideon="' . implode(',', $this->hideOn) . '"';
    }

    if (!empty($this->showOn)) {
      $html .= ' data-showon="' . implode(',', $this->showOn) . '"';
    }

    $html .= ">";

    if (!empty($this->html)) {
      $html .= $this->html;
    }

    if (!empty($this->children)) {
      for ($i = 0; $i < count($this->children); $i++) {
        $html .= $this->children[$i]->getBuffer();
      }
    }

    if ($this->close) {

      if (!empty($this->text)) {
        $html .= $this->text;
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
  protected function renderAttr(): string
  {
    $html = '';
    $attr = $this->getAttr();



    $attributes = [
      'accept' => [
        'type' => 'string',
        'elements' => ['input'],
        'related_aria' => null
      ],
      'accept-charset' => [
        'type' => 'string',
        'elements' => ['form'],
        'related_aria' => null
      ],
      'accesskey' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'action' => [
        'type' => 'string',
        'elements' => ['form'],
        'related_aria' => null
      ],
      'alt' => [
        'type' => 'string',
        'elements' => ['area', 'img', 'input'],
        'related_aria' => 'aria-label'
      ],
      'async' => [
        'type' => 'bool',
        'elements' => ['script'],
        'related_aria' => null
      ],
      'autocomplete' => [
        'type' => 'on-off',
        'elements' => ['form', 'input', 'select', 'textarea'],
        'related_aria' => null
      ],
      'autofocus' => [
        'type' => 'bool',
        'elements' => ['button', 'input', 'select', 'textarea'],
        'related_aria' => null
      ],
      'autoplay' => [
        'type' => 'bool',
        'elements' => ['audio', 'video'],
        'related_aria' => null
      ],
      'charset' => [
        'type' => 'string',
        'elements' => ['meta', 'script'],
        'related_aria' => null
      ],
      'checked' => [
        'type' => 'bool',
        'elements' => ['command', 'input'],
        'related_aria' => 'aria-checked'
      ],
      'cite' => [
        'type' => 'string',
        'elements' => ['blockquote', 'del', 'ins', 'q'],
        'related_aria' => null
      ],
      'class' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'cols' => [
        'type' => 'int',
        'elements' => ['textarea'],
        'related_aria' => null
      ],
      'colspan' => [
        'type' => 'int',
        'elements' => ['td', 'th'],
        'related_aria' => null
      ],
      'content' => [
        'type' => 'string',
        'elements' => ['meta'],
        'related_aria' => null
      ],
      'contenteditable' => [
        'type' => 'true-false',
        'elements' => ['Global attribute'],
        'related_aria' => 'aria-readonly'
      ],
      'controls' => [
        'type' => 'bool',
        'elements' => ['audio', 'video'],
        'related_aria' => null
      ],
      'coords' => [
        'type' => 'string',
        'elements' => ['area'],
        'related_aria' => null
      ],
      'crossorigin' => [
        'type' => 'string',
        'elements' => ['audio', 'img', 'link', 'script', 'video'],
        'related_aria' => null
      ],
      'data' => [
        'type' => 'string',
        'elements' => ['object'],
        'related_aria' => null
      ],
      'datetime' => [
        'type' => 'string',
        'elements' => ['del', 'ins', 'time'],
        'related_aria' => null
      ],
      'default' => [
        'type' => 'bool',
        'elements' => ['track'],
        'related_aria' => null
      ],
      'defer' => [
        'type' => 'bool',
        'elements' => ['script'],
        'related_aria' => null
      ],
      'dir' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'dirname' => [
        'type' => 'string',
        'elements' => ['input', 'textarea'],
        'related_aria' => null
      ],
      'disabled' => [
        'type' => 'bool',
        'elements' => ['button', 'command', 'fieldset', 'input', 'keygen', 'optgroup', 'option', 'select', 'textarea'],
        'related_aria' => 'aria-disabled'
      ],
      'download' => [
        'type' => 'string-bool',
        'elements' => ['a', 'area'],
        'related_aria' => null
      ],
      'draggable' => [
        'type' => 'true-false',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'dropzone' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'enctype' => [
        'type' => 'string',
        'elements' => ['form'],
        'related_aria' => null
      ],
      'for' => [
        'type' => 'string',
        'elements' => ['label', 'output'],
        'related_aria' => null
      ],
      'form' => [
        'type' => 'string',
        'elements' => ['button', 'fieldset', 'input', 'keygen', 'label', 'meter', 'object', 'output', 'progress', 'select', 'textarea'],
        'related_aria' => null
      ],
      'formaction' => [
        'type' => 'string',
        'elements' => ['input', 'button'],
        'related_aria' => null
      ],
      'formenctype' => [
        'type' => 'string',
        'elements' => ['button', 'input'],
        'related_aria' => null
      ],
      'formmethod' => [
        'type' => 'string',
        'elements' => ['button', 'input'],
        'related_aria' => null
      ],
      'formnovalidate' => [
        'type' => 'bool',
        'elements' => ['button', 'input'],
        'related_aria' => null
      ],
      'formtarget' => [
        'type' => 'string',
        'elements' => ['button', 'input'],
        'related_aria' => null
      ],
      'headers' => [
        'type' => 'string',
        'elements' => ['td', 'th'],
        'related_aria' => null
      ],
      'height' => [
        'type' => 'int',
        'elements' => ['canvas', 'embed', 'iframe', 'img', 'input', 'object', 'video'],
        'related_aria' => null
      ],
      'hidden' => [
        'type' => 'bool',
        'elements' => ['Global attribute'],
        'related_aria' => 'aria-hidden'
      ],
      'hideOn' => [
        'type' => 'array',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'high' => [
        'type' => 'number',
        'elements' => ['meter'],
        'related_aria' => null
      ],
      'href' => [
        'type' => 'string',
        'elements' => ['a', 'area', 'base', 'link'],
        'related_aria' => null
      ],
      'hreflang' => [
        'type' => 'string',
        'elements' => ['a', 'area', 'link'],
        'related_aria' => null
      ],
      'http-equiv' => [
        'type' => 'string',
        'elements' => ['meta'],
        'related_aria' => null
      ],
      'id' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'ismap' => [
        'type' => 'bool',
        'elements' => ['img'],
        'related_aria' => null
      ],
      'kind' => [
        'type' => 'string',
        'elements' => ['track'],
        'related_aria' => null
      ],
      'label' => [
        'type' => 'string',
        'elements' => ['optgroup', 'option', 'track'],
        'related_aria' => 'aria-label'
      ],
      'lang' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'list' => [
        'type' => 'string',
        'elements' => ['input'],
        'related_aria' => null
      ],
      'loop' => [
        'type' => 'bool',
        'elements' => ['audio', 'video'],
        'related_aria' => null
      ],
      'low' => [
        'type' => 'number',
        'elements' => ['meter'],
        'related_aria' => null
      ],
      'max' => [
        'type' => 'string',
        'elements' => ['input', 'meter', 'progress'],
        'related_aria' => 'aria-valuemax'
      ],
      'maxlength' => [
        'type' => 'int',
        'elements' => ['input', 'textarea'],
        'related_aria' => null
      ],
      'media' => [
        'type' => 'string',
        'elements' => ['a', 'area', 'link', 'source', 'style'],
        'related_aria' => null
      ],
      'method' => [
        'type' => 'string',
        'elements' => ['form'],
        'related_aria' => null
      ],
      'min' => [
        'type' => 'string',
        'elements' => ['input', 'meter'],
        'related_aria' => 'aria-valuemin'
      ],
      'minlength' => [
        'type' => 'int',
        'elements' => ['input', 'textarea'],
        'related_aria' => null
      ],
      'multiple' => [
        'type' => 'bool',
        'elements' => ['input', 'select'],
        'related_aria' => null
      ],
      'muted' => [
        'type' => 'bool',
        'elements' => ['audio', 'video'],
        'related_aria' => 'aria-muted'
      ],
      'name' => [
        'type' => 'string',
        'elements' => ['button', 'form', 'fieldset', 'iframe', 'input', 'keygen', 'object', 'output', 'select', 'textarea', 'map', 'meta', 'param'],
        'related_aria' => null
      ],
      'novalidate' => [
        'type' => 'bool',
        'elements' => ['form'],
        'related_aria' => null
      ],
      'open' => [
        'type' => 'bool',
        'elements' => ['details'],
        'related_aria' => null
      ],
      'optimum' => [
        'type' => 'number',
        'elements' => ['meter'],
        'related_aria' => null
      ],
      'pattern' => [
        'type' => 'string',
        'elements' => ['input'],
        'related_aria' => null
      ],
      'placeholder' => [
        'type' => 'string',
        'elements' => ['input', 'textarea'],
        'related_aria' => null
      ],
      'poster' => [
        'type' => 'string',
        'elements' => ['video'],
        'related_aria' => null
      ],
      'preload' => [
        'type' => 'string',
        'elements' => ['audio', 'video'],
        'related_aria' => null
      ],
      'readonly' => [
        'type' => 'bool',
        'elements' => ['input', 'textarea'],
        'related_aria' => 'aria-readonly'
      ],
      'rel' => [
        'type' => 'string',
        'elements' => ['a', 'area', 'link'],
        'related_aria' => null
      ],
      'required' => [
        'type' => 'bool',
        'elements' => ['input', 'select', 'textarea'],
        'related_aria' => 'aria-required'
      ],
      'reversed' => [
        'type' => 'bool',
        'elements' => ['ol'],
        'related_aria' => null
      ],
      'rows' => [
        'type' => 'int',
        'elements' => ['textarea'],
        'related_aria' => null
      ],
      'rowspan' => [
        'type' => 'int',
        'elements' => ['td', 'th'],
        'related_aria' => null
      ],
      'sandbox' => [
        'type' => 'string',
        'elements' => ['iframe'],
        'related_aria' => null
      ],
      'scope' => [
        'type' => 'string',
        'elements' => ['th', 'td'],
        'related_aria' => null
      ],
      'scoped' => [
        'type' => 'bool',
        'elements' => ['style'],
        'related_aria' => null
      ],
      'selected' => [
        'type' => 'bool',
        'elements' => ['option'],
        'related_aria' => null
      ],
      'shape' => [
        'type' => 'string',
        'elements' => ['area'],
        'related_aria' => null
      ],
      'showOn' => [
        'type' => 'array',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'size' => [
        'type' => 'int',
        'elements' => ['input', 'select'],
        'related_aria' => null
      ],
      'sizes' => [
        'type' => 'string',
        'elements' => ['img', 'link', 'source'],
        'related_aria' => null
      ],
      'span' => [
        'type' => 'int',
        'elements' => ['col', 'colgroup'],
        'related_aria' => null
      ],
      'spellcheck' => [
        'type' => 'bool',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'src' => [
        'type' => 'string',
        'elements' => ['audio', 'embed', 'iframe', 'img', 'input', 'script', 'source', 'track', 'video'],
        'related_aria' => null
      ],
      'srcdoc' => [
        'type' => 'string',
        'elements' => ['iframe'],
        'related_aria' => null
      ],
      'srclang' => [
        'type' => 'string',
        'elements' => ['track'],
        'related_aria' => null
      ],
      'srcset' => [
        'type' => 'string',
        'elements' => ['img', 'source'],
        'related_aria' => null
      ],
      'start' => [
        'type' => 'int',
        'elements' => ['ol'],
        'related_aria' => null
      ],
      'step' => [
        'type' => 'string',
        'elements' => ['input'],
        'related_aria' => null
      ],
      'style' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'tabindex' => [
        'type' => 'int',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'target' => [
        'type' => 'string',
        'elements' => ['a', 'area', 'base', 'form'],
        'related_aria' => null
      ],
      'title' => [
        'type' => 'string',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'translate' => [
        'type' => 'on-off',
        'elements' => ['Global attribute'],
        'related_aria' => null
      ],
      'type' => [
        'type' => 'string',
        'elements' => ['button', 'input', 'command', 'embed', 'object', 'script', 'source', 'style', 'menu'],
        'related_aria' => null
      ],
      'usemap' => [
        'type' => 'string',
        'elements' => ['img', 'input', 'object'],
        'related_aria' => null
      ],
      'value' => [
        'type' => 'string',
        'elements' => ['button', 'input', 'li', 'meter', 'option', 'progress', 'param'],
        'related_aria' => 'aria-valuenow'
      ],
      'width' => [
        'type' => 'int',
        'elements' => ['canvas', 'embed', 'iframe', 'img', 'input', 'object', 'video'],
        'related_aria' => null
      ],
      'wrap' => [
        'type' => 'string',
        'elements' => ['textarea'],
        'related_aria' => null
      ]
    ];

    if (count($this->css) > 0) {
      $html .= ' class="' . implode(' ', array_unique($this->css)) . '"';
    }

    for ($i = 0; $i < count($attr); $i++) {
      $key = $attr[$i];

      //if ($key == 'autocomplete') {
      //  die('<pre>' . print_r($attr, true));
      //}

      if (!array_key_exists($key, $attributes)) {
        continue;
      }

      $obj = (object)$attributes[$key];

      if ($key == 'httpequiv') {
        $key = 'http-equiv';
      }
      /*
      if ((!in_array('Global attribute', $obj->elements) && !in_array($this->tag, $obj->elements))
        || empty($this->$key)
      ) {

        // TODO: This is hacky, fix it to allow empty values for option tags
        if ($this->tag == 'option' && $key == 'value' && (empty($this->value) && $this->value != 0)) {
          $html .= ' value=""';
        }

        continue;
      }
      */

      $html .= ' ';

      switch ($obj->type) {

        case 'bool':
          $html .= $key;
          break;

        case 'true-false':
          $html .= $key . '="' . (($this->$key) ? 'true' : 'false') . '"';
          break;

        case 'on-off':
          //die('<pre>' . print_r($this, true));
          if (isset($this->type) && $this->type == 'password') {
            $html .= $key . '="' . (($this->$key) ? 'new-password' : 'off') . '"';
          } else {
            $html .= $key . '="' . (($this->$key) ? 'on' : 'off') . '"';
          }

          break;

        case 'none':
          $html .= $key . '="' . (($this->$key) ? '' : 'none') . '"';
          break;

        case 'array':
          $html .= 'data-' . $key . '="' . implode(',', $this->$key) . '"';
          break;

        default:

          if ($key == 'data' && is_array($this->$key)) {
            $d = $this->$key;

            foreach ($d as $k => $v) {
              if (!is_object($v)) {
                $html .= $key . '-' . $k . '="' . $v . '"';
              }
            }
          } else {
            if (!is_array($this->$key)) {
              $html .= $key . '="' . $this->$key . '"';
            }
          }


          break;
      }
    }

    return ' ' . trim($html);
  }

  protected function getAttr(): array
  {
    $attr = [];

    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    for ($i = 0; $i < count($properties); $i++) {

      $key  = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if (isset($this->$key) && !empty($this->$key)) {

        switch ($type) {
          case 'string':
          case 'int':
          case 'bool':
          case 'array':
            $attr[] = $key;
            break;

          default:
            break;
        }
      }
    }

    return $attr;
  }
  /*
  protected function addCondition(string $key, string $value, bool $equal = true)
  {
    $condition = $key . (($equal) ? '==' : '!=') . $value;

    if (!in_array($condition, $this->conditions)) {
      $this->conditions[] = $condition;
    }
  }
  */
}
