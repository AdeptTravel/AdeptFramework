<?php

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die();

use \Adept\Application;
use \Adept\Component;

abstract class Document
{
  /**
   * @var \Adept\Application
   */
  protected $app;

  /**
   * Undocumented variable
   *
   * @var string
   */
  protected string $buffer;

  /**
   * The component object
   *
   * @var \Adept\Component
   */
  public $component;

  public function __construct(Application &$app)
  {
    $this->app = $app;

    $category   = $app->session->request->route->category;
    $component  = $app->session->request->route->component;
    $option     = $app->session->request->route->option;
    $type       = $this->app->session->request->url->type;
    $namespace  = "\\Adept\\Component\\$category\\$component\\$type\\$option";
    $controller = FS_COMPONENT . "$category/$component/$type/$option.php";
    $template   = FS_COMPONENT . "$category/$component/$type/Template/$option.php";

    if (!class_exists($namespace)) {

      if ($type == 'HTML' && file_exists($template)) {
        // Used for HTML only, this means that there is a template file but no
        // component file.  We will load a generic component class to allow the
        // template to load.
        $namespace = "\\Adept\\Abstract\\Component";
      } else {
        //$namespace = "\\Adept\\Component\\Core\\Error\\$type\\Error";
        $namespace = "\\Adept\\Abstract\\Component";
        $this->app->session->request->setStatus(404);
      }
    }

    $this->component = new $namespace($app, $this);

    header('Content-type: ' . $app->session->request->url->mime);
  }

  abstract public function getBuffer(): string;

  public function render()
  {
    echo $this->getBuffer();
  }
}
