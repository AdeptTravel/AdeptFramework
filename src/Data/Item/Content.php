<?php

/**
 * \AdeptCMS\Data\Item\Content
 *
 * The content data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2023 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Data\Item;

use stdClass;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Data\Item\Content
 *
 * The content data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2023 The Adept Traveler, Inc., All Rights Reserved.
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
  public $title = '';


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
   * @var \AdeptCMS\Data\Item\OpenGraph
   */
  public $opengraph;

  /**
   * ID of the OpenGraph object
   * 
   * @var \AdeptCMS\Data\Item\Twitter
   */
  public $twitter;

  /**
   * @var array
   */
  public $urls;

  /**
   * @var \AdeptCMS\Data\Item\Image
   */
  public $image;

  /**
   * @var \AdeptCMS\Data\Item\Video
   */
  public $video;

  /**
   * Undocumented variable
   *
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

  public function init()
  {
    $this->route = new \AdeptCMS\Data\Item\Route($this->db, 0);
    $this->route->type = 'Component';
    $this->route->area = 'Site';
    $this->route->component = 'Content';

    $this->type = '';
    $this->parent = new \AdeptCMS\Data\Item\Content($this->db);
    $this->title = '';
    $this->summary = '';
    $this->content = '';
    $this->opengraph = new \AdeptCMS\Data\Item\OpenGraph($this->db);
    $this->twitter = new \AdeptCMS\Data\Item\Twitter($this->db);
    $this->urls = new stdClass();
    $this->image = new \AdeptCMS\Data\Item\Image($this->db);
    $this->video = new \AdeptCMS\Data\Item\Video($this->db);
    $this->media = new stdClass();
    $this->params = new stdClass();
    $this->data = new stdClass();
    $this->created = '0000-00-00 00:00:00';
    $this->modified = '0000-00-00 00:00:00';
    $this->publish = '0000-00-00 00:00:00';
    $this->unpublish = '0000-00-00 00:00:00';
    $this->order = 0;
  }

  public function save(): bool
  {
    if (empty($this->title)) {
      $this->errors[] = 'Title is required.';
    }

    if (empty($this->type)) {
      $this->errors[] = 'Content type is required.';
    }

    if ($this->route->id == 0) {
      $this->route->route = $this->generateRoute($this->parent->id, $this->title);

      if (!empty($this->route->errors)) {
        for ($i = 0; $i < count($this->route->errors); $i++) {
          $this->errors[] = $this->route->errors[$i];
        }
      }
    }


    return (empty($this->errors)) ? parent::save() : false;
  }

  protected function generateRoute(int $parent, string $title): string
  {
    $route = '';

    if (!empty($parent) || $parent == 0) {
      // Build route from parent + title
      $query  = 'WITH RECURSIVE `a` (`id`, `parent`, `title`, `path`) AS';
      $query .= '(';
      $query .= ' SELECT `id`, `parent`, `title`, `title` as `path`';
      $query .= ' FROM `content`';
      $query .= " WHERE `parent` = 0 AND `type` = 'Category'";
      $query .= ' UNION ALL';
      $query .= " SELECT `c`.`id`, `c`.`parent`, `c`.`title`, CONCAT(`b`.`path`, ' / ', `c`.`title`)";
      $query .= ' FROM `a` AS `b` JOIN content AS `c`';
      $query .= ' ON `b`.`id` = `c`.`parent`';
      $query .= " WHERE `c`.`type` = 'Category'";
      $query .= ')';
      $query .= 'SELECT `path` FROM `a`';
      $query .= ' WHERE id = ?';
      $query .= ' ORDER BY `path`;';

      $result = $this->db->getValue($query, [$parent]);

      $parts = explode('/', $result);

      for ($i = 0; $i < count($parts); $i++) {
        $parts[$i] = preg_replace("/[^a-zA-Z0-9]+/", "", $parts[$i]);
      }

      $route = implode('/', $parts) . '/' . preg_replace("/[^a-zA-Z0-9]+/", "", $title);
    } else {
      $route = preg_replace("/[^a-zA-Z0-9]+/", "", $title);
    }

    return $route;
  }
}
