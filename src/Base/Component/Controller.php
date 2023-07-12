<?php

namespace AdeptCMS\Base\Component;

defined('_ADEPT_INIT') or die('No Access');

abstract class Controller
{
  protected $app;
  protected $doc;

  public $form;
  public $model;

  public function __construct(\AdeptCMS\Application &$app, \AdeptCMS\Base\Document &$doc)
  {
    $this->app = $app;
    $this->doc = $doc;
  }

  public function onLoad()
  {
    $action = $this->app->session->request->data->getString('action');

    //file_put_contents(FS_LOG . 'listing.reorder.log', 'POST ' . print_r($_POST, true) . "\n", FILE_APPEND);

    if (!empty($action) && method_exists($this, $method = 'on' . ucfirst($action))) {
      $this->$method();
    }
  }

  public function onPost()
  {
  }

  public function onGet()
  {
  }
}
