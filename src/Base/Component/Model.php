<?php

namespace AdeptCMS\Base\Component;

defined('_ADEPT_INIT') or die('No Access');

abstract class Model
{
  use \AdeptCMS\Traits\Cache;
  use \AdeptCMS\Traits\Text;

  /**
   * @var \AdeptCMS\Application
   */
  protected $app;

  /**
   * Reference to the database object
   * 
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * Array of error messages, usually used when modifying data
   *
   * @var array
   */
  public $errors = [];

  public function __construct(\AdeptCMS\Application $app)
  {

    $this->app = $app;
    $this->db = $app->db;
  }


  public function getJSON(): string
  {
    return '';
  }

  public function mergeItem(object $a, object $b)
  {
    foreach ($a as $key => $value) {
      if (is_object($a->$key)) {
        if (isset($b->$key) && is_object($b->$key)) {
          $this->mergeItem($a->$key, $b->$key);
        }
      } else {
        if (!empty($b->$key)) {
          $a->$key = $b->$key;
        }
      }
    }

    return $a;
  }
}
