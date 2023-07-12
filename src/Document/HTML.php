<?php

namespace AdeptCMS\Document;

defined('_ADEPT_INIT') or die();

class HTML extends \AdeptCMS\Base\Document
{
  /**
   * Undocumented variable
   *
   * @var array
   */

  protected $controls;

  /**
   * Template HTML
   *
   * @var string
   */
  protected $html;

  /**
   * Array containing all forms to be rendered in the document (HTML only)
   *
   * @var array
   */
  public $forms;

  /**
   * The head object (HTML only)
   * 
   * @var \AdeptCMS\Document\HTML\Head
   */
  public $head;


  /**
   * The menu object (HTML only)
   *
   * @var \AdeptCMS\Menu
   */
  public $menu;

  /**
   * Init
   */
  public function __construct(\AdeptCMS\Application &$app)
  {
    $conf = $app->conf;

    $session = $app->session;
    $auth = $session->auth;
    $request = $session->request;
    $head = new \AdeptCMS\Document\HTML\Head($conf, $request);
    $menu = new \AdeptCMS\Document\HTML\Body\Menu($app);

    $this->controls = [];
    $this->forms = [];
    $this->html = '';

    // Uncomment to allow template to be defined via querystring
    //$template = strtolower($request->getData()->getString('template', INPUT_GET));

    $template = ($request->route->area == 'Admin') ? 'Admin' : 'Site';

    if (!empty($path = $this->matchDir(FS_TEMPLATE, $template))) {

      $template = str_replace(FS_TEMPLATE, '', $path);
      $template = substr($template, 0, strlen($template) - 1);

      $file = $path . '/Template.php';

      if (file_exists($file)) {
        ob_start();
        include($file);
        $this->html = ob_get_contents();
        ob_end_clean();
      }

      $this->head = $head;
      $this->menu = $menu;

      parent::__construct($app);
    } else {
      die('Template Error.');
    }
  }

  public function getTitle(): string
  {
    return $this->head->title;
  }

  public function setTitle(string $title)
  {
    $this->head->title = $title;
  }

  public function getDescription(): string
  {
    return $this->head->meta->description;
  }

  public function setDescription(string $description)
  {
    $this->head->meta->description = $description;
  }

  public function getForm(string $alias): \AdeptCMS\Document\HTML\Body\Form
  {
    if (!key_exists($alias, $this->forms)) {

      $this->forms[$alias] = new \AdeptCMS\Document\HTML\Body\Form(
        $this->app->db,
        $this->app->session,
        $this->head,
        $alias
      );
    }

    return $this->forms[$alias];
  }

  public function getBuffer(): string
  {

    $html = $this->html;

    if (strpos($html, '{{toolbar}}') !== false) {
      $toolbar = '';

      foreach ($this->controls as $control) {
        $toolbar .= $control;
      }

      $html = str_replace('{{toolbar}}', $toolbar, $html);
    }

    // Title
    $html = str_replace('{{title}}', $this->head->meta->title, $html);

    // Component
    $html = str_replace('{{component}}', $this->component->getBuffer(), $html);

    // Modules
    $modules = new \AdeptCMS\Document\HTML\Body\Modules($this->app, $this);

    preg_match_all('/\{\{module.+?\}\}/', $html, $matches);

    foreach ($matches[0] as $match) {
      $tag = substr($match, 2, strlen($match) - 4);
      $parts = explode(':', $tag);
      $name = $parts[1];
      $args = [];

      if (count($parts) > 2) {
        unset($parts[0]);
        unset($parts[1]);
        $args = array_values($parts);
      }

      $module = $modules->getModule($name, $args);
      $html = str_replace($match, ($module !== false) ? $module->getHTML() : '', $html);
    }

    preg_match_all('/\{\{form.+?\}\}/', $html, $matches);

    foreach ($matches[0] as $match) {
      $tag = substr($match, 2, strlen($match) - 4);
      $parts = explode(':', $tag);
      $alias = $parts[1];
      $args = [];

      if (count($parts) > 2) {
        unset($parts[0]);
        unset($parts[1]);
        $args = array_values($parts);
      }

      $form = $this->getForm($alias);

      $html = str_replace($match, $form->getHTML(), $html);
    }

    // Head
    $html = str_replace('{{head}}', $this->head->getHTML(), $html);

    return $html;
  }

  public function save()
  {
    //$html5->save($dom, 'out.html');
  }

  public function addControl(int $type)
  {
    switch ($type) {
      case CONTROL_SAVE:
        $this->controls[$type] = '<input id="save" type="button" value="Save">';
        break;
      case CONTROL_SAVE_CLOSE:
        $this->controls[$type] = '<input id="save_close" type="button" value="Save & Close">';
        break;
      case CONTROL_SAVE_COPY:
        $this->controls[$type] = '<input id="save_copy" type="button" value="Save as Copy">';
        break;
      case CONTROL_CLOSE:
        $this->controls[$type] = '<input id="close" type="button" value="Close">';
        break;
      case CONTROL_PUBLISH:
        $this->controls[$type] = '<input id="publish" type="button" value="Publish">';
        break;
      case CONTROL_UNPUBLISH:
        $this->controls[$type] = '<input id="unpublish" type="button" value="Unpublish">';
        break;
      case CONTROL_NEW:
        $this->controls[$type] = '<input id="new" type="button" value="New">';
        break;
      case CONTROL_DELETE:
        $this->controls[$type] = '<input id="delete" type="button" value="Delete">';
        break;
    }
  }
}
