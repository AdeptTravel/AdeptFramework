<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die();

use \Adept\Data\Item\Location\Country;
use \Adept\Application\Session\Request\Data\Post;

class Phone extends \Adept\Abstract\Data\Item
{

  protected string $name = 'Phone';
  protected array $postFilters = ['tel' => 'Phone'];
  protected string $table = 'phone';

  /**
   * Undocumented variable
   *
   * @var \Adept\Data\Item\Location\Country
   */
  public Country $country;

  /**
   * Undocumented variable
   *
   * @var integer
   */
  public int $tel;

  /**
   * Undocumented variable
   *
   * @var integer
   */
  public int $extension;

  public function __construct(\Adept\Application\Database $db, int $id = 0)
  {
    $this->country = new Country($db);

    parent::__construct($db, $id);
  }

  public function loadFromPost(Post $post, string $prefix = '')
  {
    parent::loadFromPost($post, $prefix);

    if (!empty($this->country->phone_code) && !empty($this->tel)) {

      $obj = $this->db->getObject(
        "SELECT * FROM `phone` WHERE `country` = ? AND `tel` = ?",
        [$this->country->id, $this->tel]
      );

      if ($obj !== false) {
        $this->loadFromObj($obj);
      }
    }
  }
}
