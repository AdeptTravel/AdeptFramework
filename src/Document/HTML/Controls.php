<?php

namespace Adept\Document\HTML;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Head;

class Controls
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Document\HTML\Head
   */
  protected Head $head;

  protected object $map;

  public bool $close = false;
  public bool $delete = false;
  public bool $duplicate = false;
  public bool $edit = false;
  public bool $new = false;
  public bool $publish = false;
  public bool $save = false;
  public bool $saveclose = false;
  public bool $savenew = false;
  public bool $unpublish = false;

  /**
   * Undocumented function
   *
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct(Head &$head)
  {

    $this->head = $head;

    $this->map = (object)[
      'close'     => (object)['fa' => 'fa-xmark', 'title' => 'Close'],
      'delete'    => (object)['fa' => 'fa-trash-can', 'title' => 'Delete'],
      'duplicate' => (object)['fa' => 'fa-regular fa-clone', 'title' => 'Duplicate'],
      'edit'      => (object)['fa' => 'fa-pen-to-square', 'title' => 'Edit'],
      'new'       => (object)['fa' => 'fa-plus', 'title' => 'New'],
      'publish'   => (object)['fa' => 'fa-check', 'title' => 'Publish'],
      'save'      => (object)['fa' => 'fa-regular fa-floppy-disk', 'title' => 'Save'],
      'saveclose' => (object)['fa' => 'fa-regular fa-floppy-disk', 'title' => 'Save &amp; Close'],
      'savenew'   => (object)['fa' => 'fa-plus', 'title' => 'Save &amp; New'],
      'unpublish' => (object)['fa' => 'fa-xmark', 'title' => 'Unpublish']
    ];
  }

  public function getBuffer(): string
  {
    $buffer = '';

    //print_r($this);
    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    foreach ($properties as $p) {

      $key = $p->name;
      if ($this->$key) {
        $buffer .= '<input type="button" class="' . $key . '"';
        $buffer .= ' value = "';
        $buffer .= '<i class="fa-solid ';
        $buffer .= $this->map->$key->fa;
        $buffer .= '"></i>';
        $buffer .= $this->map->$key->title;
        $buffer .= '"';
        $buffer .= '>';
      }
    }

    return $buffer;
  }
}
