<?php

/**
 * \AdeptCMS\Data\Item\Route
 *
 * The route data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Data\Item;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Data\Item\Route
 *
 * The route data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Content extends \AdeptCMS\Base\Data\Item
{

  /**
   * @var \AdeptCMS\Data\Item\Route
   */
  public $route;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $type;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Data\Item\Content
   */
  public $parent;

  /**
   * @var string
   */
  public $title;


  /**
   * @var string
   */
  public $summary = '';
  /**
   * @var string
   */
  public $content = '';

  /**
   * ID of the OpenGraph object
   * 
   * @var int
   */
  public $opengraph;

  /**
   * ID of the Twitter object
   * 
   * @var int
   */
  public $twitter;

  /**
   * @var array
   */
  public $urls;

  /**
   * @var object
   */
  public $media;

  /**
   * @var object
   */
  public $params;

  /**
   * @var object
   */
  public $data;

  /**
   * @var string
   */
  public $created;

  /**
   * @var string
   */
  public $modified;

  /**
   * @var string
   */
  public $deleted;

  /**
   * @var string
   */
  public $publish;

  /**
   * @var string
   */
  public $unpublish;

  /**
   * @var int
   */
  public $order;

  public function load(
    int|string $id,
    string $col = 'id'
  ): bool {

    $status = parent::load($id, $col);

    $this->parent = new \AdeptCMS\Data\Item\Content(
      $this->db,
      (is_int($this->parent)) ? $this->parent : 0
    );

    if ($this->route > 0) {
      $this->route = new \AdeptCMS\Data\Item\Route($this->db, $this->route);
    }

    return $status;
  }

  public function save(): bool
  {
    return parent::save();
  }
}
