<?php

namespace AdeptCMS\Data\Item;

defined('_ADEPT_INIT') or die();

class Address extends \AdeptCMS\Base\Data\Item
{

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $title = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $street0 = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $street1 = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $street2 = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $city = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $county = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $region = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $postalcode = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $country = '';

  /**
   * Undocumented variable
   *
   * @var integer
   */
  public $elevation = 0;

  /**
   * Undocumented variable
   *
   * @var float
   */
  public $latitude = 0.000;

  /**
   * Undocumented variable
   *
   * @var float
   */
  public $longitude = 0.000;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $timezone = '';

  public function __construct(\AdeptCMS\Application\Database $db, int $id = 0)
  {
    $this->table = 'address';
    parent::__construct($db, $id);
  }
}
