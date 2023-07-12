<?php

namespace AdeptCMS\Base\Component;

defined('_ADEPT_INIT') or die('No Access');

abstract class Router
{
  use \AdeptCMS\Traits\FileSystem;

  /**
   * A reference to the Application object
   *
   * @var \AdeptCMS\Application
   */
  protected $app;

  /**
   * A reference to the Document object
   *
   * @var \AdeptCMS\Base\Document
   */
  protected $doc;

  /**
   * Component namespace
   *
   * @var string
   */
  protected $namespace;

  /**
   * The current route
   *
   * @var string
   */
  protected $route;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected $filter = [];

  /**
   * MVC - Controller
   *
   * @var \AdeptCMS\Base\Component\Controller
   */
  public $controller;

  /**
   * MVC - Model
   *
   * @var \AdeptCMS\Base\Component\Model
   */
  public $model;

  /**
   * MVC - View
   *
   * @var \AdeptCMS\Base\Component\View
   */
  public $view;

  public $authRequired = false;

  public function __construct(
    \AdeptCMS\Application &$app,
    \AdeptCMS\Base\Document &$doc
  ) {

    $request = $app->session->request;

    $this->app = $app;
    $this->doc = $doc;

    $this->namespace = '\\Component\\'
      . $request->route->component
      . '\\' . $app->session->request->route->area;

    $root = '\\AdeptCMS\\Component\\'
      . $this->app->session->request->route->area;

    // Initilize empty model
    $namespace = $root . '\\Model';
    $this->model = new $namespace($this->app);

    // Initilize empty controller
    $namespace = $root . "\\Controller";
    $this->controller = new $namespace($app, $doc);
    $this->controller->model = $this->model;

    // Initilize empty view
    $namespace = $root . "\\View\\" . $app->session->request->url->type;

    if (!class_exists($namespace)) {
      $namespace = "\\AdeptCMS\\Base\\Component\\View\\" . $app->session->request->url->type;
    }
    $this->view = new $namespace($app, $doc);
    $this->view->model = $this->model;
    $this->view->controller = $this->controller;
  }

  /**
   * Set the controller
   *
   * @param string $controller
   * @return void
   */
  public function setController(string $controller)
  {
    $namespace = $this->namespace . "\\Controller\\" . $controller;
    $this->controller = new $namespace($this->app, $this->doc);
    $this->controller->model = $this->model;
    $this->view->controller = $this->controller;
  }

  /**
   * Set the model
   *
   * @param string $model
   * @return void
   */
  public function setModel(string $model)
  {
    $namespace = $this->namespace . "\\Model\\" . $model;
    $this->model = new $namespace($this->app);

    $this->controller->model = $this->model;
    $this->view->model = $this->model;
  }

  /**
   * Setter - View Name
   *
   * @param string $view
   * @return void
   */
  public function setView(string $view)
  {
    $namespace = $this->namespace
      . '\\View\\'
      . $this->app->session->request->url->type
      . '\\' . $view;

    $this->view = new $namespace($this->app, $this->doc);
    $this->view->model = $this->model;
    $this->view->controller = $this->controller;
  }

  public function getClassName(string $type, string $option = ''): string
  {
    $classname = '';

    $path = $this->convertNamespaceToPath($this->namespace) . $type . '/';

    $file = ((!empty($option)) ? $option : $type) . '.php';

    if ($type == 'View') {
      $path .= $this->app->session->request->url->type . '/';
    }

    if (file_exists($path)) {
      $path = $this->matchFile($path, $file);

      if (!empty($path)) {
        $classname = $this->convertPathToClass($path);
      }
    }

    return $classname;
  }

  public function buildRoute(string $option = '')
  {
    $request = $this->app->session->request;

    if (empty($option)) {
      $option = $request->route->option;

      if (empty($option)) {
        $option = 'Default';
      }
    }

    // Note: Always load Model - Controller - View

    if (!empty($model = $this->getClassName('Model', $option))) {
      $this->setModel($model);
    }

    if (!empty($controller = $this->getClassName('Controller', $option))) {
      $this->setController($controller);
    }

    if (!empty($view = $this->getClassName('View', $option))) {
      $this->setView($view);
    }

    if (
      $this->app->session->request->url->type == 'HTML'
      && !empty($template = $this->getClassName('Template', $option))
    ) {

      $this->view->template = $template;
    }

    $this->onLoad();
  }

  public function runEvents()
  {
    if (!empty($_POST)) {
      $this->controller->onPost();
    }

    if (!empty($_GET)) {
      $this->controller->onGet();
    }
  }

  public function onBeforeInit()
  {
  }
  public function onAfterInit()
  {
  }

  public function onModelSetStart()
  {
  }

  public function onModelSetEnd()
  {
  }

  public function onControllerSetStart()
  {
  }

  public function onControllerSetEnd()
  {
  }

  public function onViewSetStart()
  {
  }

  public function onViewSetEnd()
  {
  }

  public function onLoad()
  {
    $this->controller->onLoad();
  }

  public function onRenderStart()
  {
    $this->view->onRenderStart();
  }

  public function onRenderEnd()
  {
    $this->view->onRenderEnd();
  }
}
