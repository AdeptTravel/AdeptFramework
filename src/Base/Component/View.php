<?php

namespace AdeptCMS\Base\Component;

defined('_ADEPT_INIT') or die('No Access');

class View
{
  /**
   * @var \AdeptCMS\Application
   */
  protected $app;

  /**
   * @var \AdeptCMS\Document
   */
  protected $doc;

  /**
   * @var \AdeptCMS\Base\Component\Controller
   */
  public $controller;

  /**
   * @var \AdeptCMS\Base\Component\Model
   */
  public $model;

  /**
   * @var array
   */
  protected $params;



  public function __construct(\AdeptCMS\Application &$app, \AdeptCMS\Base\Document &$doc)
  {
    $this->app = $app;
    $this->doc = $doc;
    $this->params = [];

    $this->onLoad();
  }

  public function getParam(string $key, string|int|bool|null $default = null): string|int|bool|null
  {
    if (in_array($key, $this->params)) {
      return $this->params[$key];
    }

    return $default;
  }

  public function setParam(string $key, string|int|bool $value)
  {
    $this->params[$key] = $value;
  }



  public function onLoad()
  {
  }

  public function onRenderStart()
  {
  }

  public function onRenderEnd()
  {
  }
}
