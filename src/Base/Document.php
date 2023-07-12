<?php

namespace AdeptCMS\Base;

defined('_ADEPT_INIT') or die();

abstract class Document
{
  use \AdeptCMS\Traits\FileSystem;

  /**
   * @var \AdeptCMS\Application
   */
  protected $app;
  protected $asset;
  protected $buffer;
  protected $directory;
  protected $file;
  protected $format;
  protected $type;

  /**
   * The component object
   *
   * @var \AdeptCMS\Component
   */
  public $component;

  public function __construct(\AdeptCMS\Application &$app)
  {
    $this->app = $app;

    $route = $app->session->request->route;

    if ($route->type == 'Asset') {
      $this->file = $route->file;
    } else {
      $this->component = new \AdeptCMS\Component($app, $this);
    }

    header('Content-type: ' . $app->session->request->url->mime);
  }

  abstract public function getBuffer(): string;

  public function render()
  {
    $this->onRenderStart();
    echo $this->getBuffer();
    $this->onRenderEnd();
  }

  public function onRenderStart()
  {
    if (isset($this->component)) {
      $this->component->onRenderStart();
    }
  }

  public function onRenderEnd()
  {
    if (isset($this->component)) {
      $this->component->onRenderEnd();
    }
  }
}
