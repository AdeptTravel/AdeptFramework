<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;
use \Adept\Application\Session\Request\Data\Post;
use \Adept\Data\Item\Location\Area;

class Location extends \Adept\Abstract\Data\Item
{

  protected array $excludeKeys = [
    'street2',
    'elevation',
    'latitude',
    'longitude',
    'timezone'
  ];

  protected string $name = 'Location';

  protected array $postFilters = [
    'street0' => 'Address'
  ];

  protected string $table = 'location';

  /**
   * Undocumented variable
   *
   * @var \Adept\Data\Item\Location\Area
   */
  public Area $area;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $title = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $street0 = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $street1 = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $street2 = '';


  /**
   * Undocumented variable
   *
   * @var integer
   */
  public int $elevation = 0;

  /**
   * Undocumented variable
   *
   * @var float
   */
  public $latitude = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $longitude = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $timezone = '';

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  int                         $id
   */
  public function __construct(Database $db, int $id = 0)
  {
    $this->area = new Area($db);

    parent::__construct($db, $id);
  }

  public function loadFromPost(Post $post, string $prefix = '')
  {
    parent::loadFromPost($post, $prefix);

    if (isset($this->street0) && $this->area->id > 0) {

      if (!empty($this->street1)) {

        $obj = $this->db->getObject(
          "SELECT * FROM `location` WHERE `street0` = ? AND `street1` = ? AND `area` = ?",
          [$this->street0, $this->street1, $this->area->id]
        );
      } else {
        $obj = $this->db->getObject(
          "SELECT * FROM `location` WHERE `street0` = ? AND `area` = ?",
          [$this->street0, $this->area->id]
        );
      }

      if ($obj !== false) {
        $this->loadFromObj($obj);
      }
    }
  }
}
