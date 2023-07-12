<?php

namespace AdeptCMS\Base\Document\HTML\Body;

use AdeptCMS\CMSClass;

defined('_ADEPT_INIT') or die();

abstract class Element
{

  use \AdeptCMS\Traits\FileSystem;

  /**
   * @var string
   */
  protected $alias;

  /**
   * @var array
   */
  public $children;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected $conditions;

  /**
   * The database object
   *
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * @var \AdeptCMS\Document\HTML\Head
   */
  protected $head;

  /**
   * @var string
   */
  protected $html;

  /**
   * @var object
   */
  public $params;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Application\Session
   */
  protected $session;

  /**
   * @var string
   */
  public $title;

  /**
   * @var string
   */
  protected $type;

  public $value;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public $index = [];

  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Application\Session &$session,
    \AdeptCMS\Document\HTML\Head &$head,
    object &$element,
    string $alias = ''
  ) {

    $this->db = $db;
    $this->session = $session;
    $this->head = $head;

    if (!empty($alias)) {
      $this->alias = $alias;
    }

    if (isset($element->params)) {
      $this->params = $element->params;
    }

    $this->title = (!empty($element->title)) ? $element->title : '';

    if (isset($element->type)) {
      $this->type = $element->type;
    }

    $this->children = [];

    if (isset($element->conditions)) {
      $this->conditions = $element->conditions;
    }

    if (isset($element->children)) {
      for ($i = 0; $i < count($element->children); $i++) {
        $this->addChild($element->children[$i]);
      }

      $this->buildIndex();
    }
  }

  /**
   * TODO: This should be merged into the __construct function right after the for loop
   * addChild is called.
   *
   * @return void
   */
  protected function buildIndex()
  {
    foreach ($this->children as $k => &$v) {
      if (substr($v->alias, -1, 1) != '_') {

        if (is_subclass_of($v, 'AdeptCMS\Base\Document\HTML\Body\Form\Element')) {

          $this->index[$k] = $v;
        }

        if (!empty($v->index)) {
          $this->index = array_merge($this->index, $v->index);
        }
      }
    }
  }

  public function addChild(object $child)
  {
    // TODO: Add params to decide if alias/name is a hierarchical list
    $alias = str_replace('.', '_', $this->alias) . '_' . $child->alias;
    //$alias = $child->alias;

    $search = [
      "\\Component\\" . $this->session->request->route->component
        . "\\" . $this->session->request->route->area
        . "\\Form\\Element",
      "\\AdeptCMS\\Document\\HTML\\Body\\Element"
    ];

    foreach ($search as $namespace) {
      if (strpos($child->type, '.') !== false) {
        foreach (explode('.', $child->type) as $part) {
          $namespace .= "\\" . $part;
        }
      } else {
        $namespace .= "\\" . $child->type;
      }

      if (isset($child->params->type)) {
        $namespace .= "\\" . ucfirst($child->params->type);
      }

      if (!empty($ns = $this->matchNamespace($namespace))) {
        $namespace = $ns;
      }

      $path = $this->convertNamespaceToPath($namespace);
      $path = substr($path, 0, strlen($path) - 1) . '.php';

      if (file_exists($path)) {
        $this->children[$alias] = new $namespace(
          $this->db,
          $this->session,
          $this->head,
          $child,
          $alias
        );

        break;
      }
    }
  }

  abstract public function getHTML(string $value = ''): string;

  protected function getHTMLConditions(object $conditions): string
  {
    $html = '';


    if (isset($conditions->action) && isset($conditions->rules)) {

      $json = json_encode(array(
        'container' => ((isset($conditions->container)) ? $conditions->container :  '.row'),
        'action' => $conditions->action,
        'logic' => ((isset($conditions->logic)) ? $conditions->logic :  'or'),
        'rules' => $conditions->rules,
      ));

      $html = ' data-conditional-rules="' . htmlspecialchars($json) . '"';;
    }

    return $html;
  }

  protected function getChild(string $alias): \AdeptCMS\Base\Document\HTML\Body\Element|null
  {
    $child = null;

    if (array_key_exists($alias, $this->children)) {
      $child = $this->children[$alias];
    } else if (array_key_exists($alias, $this->index)) {
      $child = $this->index[$alias];
    } else {
      $parts = explode('_', $alias);
      $last = $parts[count($parts) - 1];

      foreach ($this->index as $k => &$v) {
        if (strpos($k, $alias) !== false) {
          $child = $v;
          break;
        }
      }

      $parts = explode('_', $alias);
      $last = $parts[count($parts) - 1];

      foreach ($this->index as $k => &$v) {
        if (($pos = strpos($k, $last)) !== false && strlen($k) == strlen($last) + $pos) {
          $child = &$v;
          break;
        }
      }
    }

    return $child;
  }

  /**
   * Get a formatted list of element attribures
   *
   * @param   string  Element type, ie. input, textarea, form, etc.
   * @param   object  Element parameters
   *
   * @return  string  Formatted string of elements attribs to be dropped into
   *                  the HTML tag.
   */
  public static function getAttributes(object $params): string
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
      ['name' => 'css',           'type' => 'string',      'alias' => 'class'],
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


    foreach ($attributes as $attribute) {

      $key = $attribute['name'];

      if (!empty($params->$key)) {

        if (isset($attribute['alias'])) {
          $html .= ' ' . $attribute['alias'];
        } else {
          $html .= ' ' . $key;
        }

        if ($attribute['type'] != 'attrib') {

          switch ($attribute['type']) {

            case 'bool':
              $html .= '="' . (($params->$key) ? 'true' : 'false') . '"';
              break;

            case 'string':
              $html .= '="' . (string)$params->$key . '"';
              break;

            case 'int':
              $html .= '="' . (int)$params->$key . '"';
              break;

            case 'yes-no':
              $html .= '="' . (($params->$key) ? 'on' : 'off') . '"';
              break;

            case 'none':
              $html .= '="' . (($params->$key) ? '' : 'none') . '"';
              break;
          }
        }
      }
    }

    return ' ' . trim($html);
  }
}
