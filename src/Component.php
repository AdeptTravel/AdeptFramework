<?php

namespace AdeptCMS;



defined('_ADEPT_INIT') or die();

/**
 * Component class
 */
class Component
{
  protected $app;
  protected $doc;
  protected $router;

  public function __construct(
    \AdeptCMS\Application &$app,
    \AdeptCMS\Base\Document &$doc
  ) {
    $this->app = $app;
    $this->doc = $doc;

    $session = $app->session;
    $request = $session->request;
    $route = $request->route;

    $component = $request->route->component;

    if ($request->status != 200 || $route->type == 'Error') {
      $component = 'Error';
    }

    $namespace = '';
    $slug = $component . '/' . $route->area . '/Router';

    if ($component != 'Error' && file_exists(FS_COMPONENT . '/'  . $component . "/" . $route->area)) {
      $namespace = "\\Component\\" . str_replace('/', "\\", $slug);

      if ($component != 'Error' && !file_exists(FS_COMPONENT . $slug . '.php')) {
        $namespace = "\\AdeptCMS\\Component\\" . $route->area . "\\Router";
      }
    } else {
      if (DEBUG) {
        //die('\AdeptCMS\Component::__construct - Component not found(' . FS_COMPONENT  . $component . "/" . $route->area . ')');
      } else {
        //die();
      }

      $component = 'Error';
      $namespace = "\\Component\\Error\\Site\\Router";
    }

    $this->router = new $namespace($app, $doc);

    if (
      !$session->auth->status
      && $component != 'Error'
      && $session->request->route->route != 'login'
      && $session->request->route->route != 'admin/login'
      && ($route->area == 'Admin' || $this->router->authRequired)
    ) {
      $_SESSION['redirect'] = $session->request->route->route;
      header('Location: ' . (($route->area == 'Admin') ? '/admin' : '') . '/login', true, 302);
      die();
    }

    $this->router->buildRoute();
    $this->router->runEvents();
  }


  public function getBuffer(): string
  {
    return $this->router->view->getBuffer();
  }

  public function onBeforeInit()
  {
  }
  public function onAfterInit()
  {
  }

  public function onRenderStart()
  {
    $this->router->onRenderStart();
  }

  public function onRenderEnd()
  {
    $this->router->onRenderEnd();
  }
}
