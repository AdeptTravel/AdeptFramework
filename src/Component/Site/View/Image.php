<?php

namespace AdeptCMS\Component;

defined('_ADEPT_INIT') or die('No Access');

class View
{
  protected $app;

  /**
   * @var \AdeptCMS\Component\Model
   */
  protected $model;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Component\Controller
   */
  protected $controller;

  /**
   * The default template
   *
   * @var string
   */
  protected $template;

  public function __construct(\AdeptCMS\Application &$app)
  {
    $this->app = $app;
  }

  public function getModel(): \AdeptCMS\Component\Model
  {
    return $this->model;
  }

  public function setModel(\AdeptCMS\Component\Model &$model)
  {
    $this->model = $model;
  }

  public function getController(): \AdeptCMS\Component\Controller
  {
    return $this->controller;
  }

  public function setController(\AdeptCMS\Component\Controller &$controller)
  {
    $this->controller = $controller;
  }

  public function getTemplate(string $template = ''): string
  {
    if (empty($template)) {
      $template = (!empty($this->template)) ? $this->template : 'Default';
    }

    $request = $this->app->session->request;
    $area = $request->getArea();
    $format = $request->getFile()->getFormat();
    $component = $request->route->getComponent();

    $path  = FS_COMPONENT . $component . '/' . $area . '/Template/';
    $file = $path . $template . '.php';

    if (!file_exists($file)) {
      // TODO: Error, no template
      die('File NOT Exists: ' . $file);
    }

    return $file;
  }

  public function setTemplate(string $template)
  {
    $this->template = $template;
  }

  public function getHTML(string $template = ''): string
  {
    $html = '';

    ob_start();

    include($this->getTemplate($template));

    $html = ob_get_contents();

    ob_end_clean();

    return $html;
  }

  public function getBuffer(): string
  {
    $buffer = '';

    $request = $this->app->session->request;
    $format = $request->getFile()->getFormat();

    if (!isset($this->buffer) && method_exists($this, $method = 'get' . ucfirst($format))) {
      $buffer = $this->$method();
    } else {
      throw new \AdeptCMS\Exceptions\Format\Unsupported(
        'File Format Unsupported',
        "The file format '" . $format . "' is unsupported.",
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    return $buffer;
  }
}
