<?php

namespace Adept\Data\Item\Location;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;
use \Adept\Application\Session\Request\Data\Post;
use \Adept\Data\Item\Location\Country;

class Area extends \Adept\Abstract\Data\Item
{
  protected string $name = 'Area';
  protected string $table = 'location_area';

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $id = 0;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $postalcode = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $city = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $county = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $state = '';

  /**
   * Undocumented variable
   *
   * @var use \Adept\Data\Item\Location\Country
   */
  public \Adept\Data\Item\Location\Country $country;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $timezone = '';

  /**
   * Undocumented variable
   *
   * @var float
   */
  public string $latitude = '';

  /**
   * Undocumented variable
   *
   * @var float
   */
  public string $longitude = '';

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  int                         $id
   */
  public function __construct(Database $db, int $id = 0)
  {
    $this->country = new \Adept\Data\Item\Location\Country($db);

    parent::__construct($db, $id);
  }

  public function loadFromPost(Post $post, string $prefix = '')
  {
    parent::loadFromPost($post, $prefix);

    if (isset($this->city) && isset($this->county) && isset($this->state) && isset($this->postalcode)) {
      $obj = $this->db->getObject(
        "SELECT * FROM `location_area` WHERE `postalcode` = ? AND `state` = ?  AND `county` = ? AND `city` = ?",
        [$this->postalcode, $this->state, $this->county, $this->city]
      );

      if ($obj !== false) {
        $this->loadFromObj($obj);
      } else {
        $this->country->loadFromPost($post, $prefix);
      }
    } else if (!empty($this->postalcode) && !empty($this->state) && !empty($this->city)) {
      $obj = $this->db->getObject(
        "SELECT COUNT(*) AS `total`, * FROM `location_area` WHERE `postalcode` = ? AND `state` = ?  AND `city` = ?",
        [$this->postalcode, $this->state, $this->city]
      );

      if ($obj !== false && $obj->total > 1) {
        $this->loadFromObj($obj);
      } else {
        $this->country->loadFromPost($post, $prefix);
      }
    } else {
      $this->country->loadFromPost($post, $prefix);
    }
  }
}
