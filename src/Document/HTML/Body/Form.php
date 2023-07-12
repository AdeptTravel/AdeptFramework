<?php

namespace AdeptCMS\Document\HTML\Body;

use AdeptCMS\Document\HTML\Body\Element\P;

defined('_ADEPT_INIT') or die();

class Form extends \AdeptCMS\Base\Document\HTML\Body\Element
{
  use \AdeptCMS\Traits\FileSystem;
  use \AdeptCMS\Traits\HTML\Form;

  /**
   * @var array
   */
  protected $data = [];

  /**
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * Errors
   *
   * @var array
   */
  public $errors;

  /**
   * Form path
   * 
   * @var string
   */
  protected $path;

  /**
   * @var string
   */
  public $title;

  /**
   * Unique variable to check for spam/bot or other bullshit
   *
   * @var string
   */
  public $token;

  /**
   * Form constructor
   *
   * @param \AdeptCMS\Application\Database $db
   * @param \AdeptCMS\Application\Session $session
   * @param \AdeptCMS\Document\HTML\Head $head
   * @param string $alias
   */

  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Application\Session &$session,
    \AdeptCMS\Document\HTML\Head &$head,
    string $alias
  ) {

    $element = new \stdClass();

    parent::__construct($db, $session, $head, $element, $alias);

    // TODO: Add toggle in form params
    $this->head->javascript->addFile('/js/form.js');
    $this->head->javascript->addFile('/js/mf-conditional-fields.js');

    $this->children = [];
    $this->errors = [];

    $route = $session->request->route;
    $component = $session->request->route->component;
    $parts = explode('.', $alias);
    $path = '';

    if (count($parts) == 2 && $parts[0] == strtolower($component)) {
      $this->path = FS_COMPONENT . $component . '/' . $route->area . '/Form/';
      $path =  $this->matchFile($this->path,  $parts[1] . '.json');
    } else {
      $this->path = $this->matchPath(FS_FORM);
      $path = $this->matchFile($this->path, $parts[1] . '.json');
    }

    if (empty($path)) {
      // TODO: Add exception here
      die('Form ' . $alias . ' not found');
    }

    $json = file_get_contents($path);
    $form = json_decode($json);

    if ($form === false) {
      // TODO: Add exception here
      die('Form ' . $alias . ' is invalid.');
    }

    $this->alias = $alias;
    $this->title = $form->title;
    $this->params = $form->params;

    for ($i = 0; $i < count($form->children); $i++) {
      $this->addChild($form->children[$i]);
    }

    $this->buildIndex('', $form->children);

    if (!isset($_SESSION['form.token'])) {
      $_SESSION['form.token'] = $this->generateToken();
    }

    $this->token = $_SESSION['form.token'];
  }

  public function getActive(): bool
  {
    $data = $this->session->request->data;

    $alias = $data->getString('alias');
    $token = $data->getString('token');

    return ($alias !== null && $alias == $this->alias && $token !== null);
  }

  public function getHTML(string|int $value = ''): string
  {
    $id = str_replace('.', '_', $this->alias);

    $this->head->css->addFile('/css/form.css');

    if (!isset($this->html)) {
      $error = (!empty($this->errors));
      $html  = '<form';
      $css = [];

      if ($this->getActive() && $error) {
        //$html .= ' class="error"';
        $css[] = 'error';
      }

      if (!empty($this->params->type)) {
        $css[] = $this->params->type;
      }

      if (!empty($css)) {
        $html .= ' class="' . implode(' ', $css) . '"';
      }

      $html .= ' id="' . $id . '"';
      $html .= ' name="' . $this->alias . '"';

      $html .= $this->getAttributes($this->params);

      if (!empty($this->params->css)) {
        $html .= ' class="' . $this->params->css . '"';
      }

      $html .= '>';

      if (!empty($this->params->type) && $this->params->type == 'admin') {
        $html .= $this->getHTMLControls();
      }

      if ($this->getActive() && $error) {
        $html .= '<div class="message error">';
        $html .= '<h3>Please fix the following errors.</h3>';
        $html .= '<ul>';

        foreach ($this->errors as $e) {
          $html .= '<li>' . $e . '</li>';
        }

        $html .= '</li>';
        $html .= '</div>';
      }

      $tabs = '';
      $children = '';

      foreach ($this->children as $child) {
        if (
          $child->type == 'Fieldset'
          && (strpos($child->params->css, ' tab ') !== false
            || $child->params->css == 'tab')
        ) {
          $tabs .= '<button data-container="' . $child->alias . '">' . $child->title . '</button>';
        }

        $children .= $child->getHTML(
          (array_key_exists($child->alias, $this->data))
            ? $this->data[$child->alias]
            : ''
        );
      }

      if (!empty($tabs)) {
        $pos = strpos($children, '<fieldset class="tab');
        //die('Pos: ' . $pos);
        $children = substr($children, 0, $pos) . '<div class="tabs">' . $tabs . '</div>' . substr($children, $pos);
      }

      $html .= $children;

      if ($this->params->type != 'admin') {
        $html .= $this->getHTMLControls();
      }

      $html .= '<input type="hidden" name="alias" value="' . $this->alias . '">';
      $html .= '<input type="hidden" name="token" value="' . $this->token . '">';
      $html .= '<input type="hidden" name="method" value="">';

      $html .= '</form>';

      $html .= '<script>';
      //$html .= 'mfConditionalFields(\'#' . $id . '\');';
      //$html .= 'mfConditionalFields(\'form\');';
      $html .= 'mfConditionalFields(\'#' . $id . '\', {';
      $html .= 'rules: \'inline\',';
      $html .= 'disableHidden: true,';
      $html .= 'depth: 7,';
      $html .= 'debug: true';
      $html .= '});';
      $html .= '</script>';

      $this->html = $html;
    }

    return $this->html;
  }

  protected function getHTMLControls(): string
  {
    $html = '<div class="controls">';

    for ($i = 0; $i < count($this->params->controls); $i++) {

      $html .= '<button';

      if ($this->params->controls[$i]->action == 'submit') {
        $html .= ' type="submit"';
      }

      if (!empty($this->params->controls[$i]->id)) {
        $html .= ' id="' . $this->params->controls[$i]->id . '"';
      }

      if (!empty($this->params->controls[$i]->css)) {
        $html .= ' class="' . $this->params->controls[$i]->css . '"';
      }

      $html .= '>';

      if ($this->params->controls[$i]->action == 'link') {
        $html .= '<a href="' . $control->url . '">';
      }

      if (isset($this->params->controls[$i]->fa)) {
        $html .= '<i class="' . $this->params->controls[$i]->fa . '"></i>';
      }

      $html .= $this->params->controls[$i]->title;

      if ($this->params->controls[$i]->action == 'link') {
        $html .= '</a>';
      }

      $html .= '</button>';
    }

    $html .= '</div>';

    return $html;
  }

  public function setValue(string $key, string $value)
  {
    $child = $this->getChild($key);

    if ($child !== null) {
      $child->value = $value;
    } else {
      echo "<div>Can't set $key to $value</div>";
    }
  }

  public function validate()
  {
    /*
    $data = $this->session->request->getData();

    $alias = $data->getString('alias', INPUT_POST);
    $token = $data->getString('token', INPUT_POST);

    if ($alias == $this->alias) {
      if ($token !== null && $alias !== null) {

        if ($this->token != $token) {
          $this->setError('Security Token Error');
        }

        foreach ($this->fields as $field) {
          // Not every element in the form is a field, we can have h2 or even p elements
          if (method_exists($field, 'getErrors')) {
            foreach ($field->getErrors() as $error) {
              $this->setError($error);
            }
          }
        }
      }
    }
    */
    return true;
  }

  public function generateToken(int $length = 16): string
  {
    $token = '';
    $chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890', 1);

    for ($i = 0; $i < $length; $i++) {
      $token .= $chars[mt_rand(0, count($chars) - 1)];
    }

    return $token;
  }
}
