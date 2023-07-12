<?php

namespace AdeptCMS\Base\Component\Model;

defined('_ADEPT_INIT') or die('No Access');

abstract class Items extends \AdeptCMS\Base\Component\Model
{
  /**
   * Array of data items
   *
   * @var array
   */
  public $items;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public $filter = [];

  public function __construct(\AdeptCMS\Application $app, array $filter = [])
  {
    parent::__construct($app);
    $this->filter = $filter;
  }

  protected function filter(array $fields = [])
  {
    for ($i = 0; $i < count($fields); $i++) {
      if (array_key_exists($fields[$i], $this->app->session->request->url->querystring)) {
        $this->filter[$fields[$i]] = $this->app->session->request->url->querystring[$fields[$i]];
      }
    }
  }

  public function getJSON(): string
  {
    return json_encode($this->items);
  }
}
