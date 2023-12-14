<?php

namespace Adept\Data\Items;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Content extends \Adept\Abstract\Data\Items
{
  protected string $name = 'Content';
  protected string $table = 'content';

  /**
   * The parent, use -1 to show all
   *
   * @var int
   */
  public int $parent = 0;

  /**
   * ` ENUM('Article', 'Category', 'Tag'),
   *
   * @var string
   */
  public string $type = 'Article';

  /**
   * Content status, use -1 to show all
   *
   * @var bool
   */
  public bool $status = true;

  public \DateTime $publish_start;
  public \DateTime $publish_end;
  public \DateTime $created;
  public \DateTime $modified;

  public function __construct(Database &$db)
  {
    /*
    $this->created       = new \DateTime('now');
    $this->modified      = new \DateTime('now');
    $this->publish_start = new \DateTime('now');
    $this->publish_end   = new \DateTime('now');
    */

    parent::__construct($db);
  }

  public function load(array $filter = []): bool
  {
    //$status = $this->loadCache();
    $status = false;
    $params = [];

    $query  = 'SELECT a.id, a.parent, b.route, a.type, a.subtype, a.title, a.summary, a.seo, a.params, a.status, a.publish_start, a.publish_end, a.created, a.modified, a.order';
    $query .= ' FROM `' . $this->table . '` AS a';
    $query .= ' INNER JOIN `route` AS b ON a.route = b.id';
    $query .= ' WHERE a.type = ?';
    $params[] = $this->type;

    $query .= ' AND b.status = 1';

    if ($this->parent >= 0) {
      $query .= ' AND parent = ?';
      $params[] = $this->parent;
    }

    if ($this->status >= 0) {
      $query .= ' AND a.status > ?';
      $params[] = $this->status;
    }

    if (isset($this->created)) {
      $query .= ' AND a.created > ?';
      $params[] = $this->created->format('Y-m-d H:i:s');
    }

    if (isset($this->modified)) {
      $query .= ' AND a.modified < ?';
      $params[] = $this->modified->format('Y-m-d H:i:s');
    }

    if (isset($this->publish_start)) {
      $query .= ' AND a.publish_start < ?';
      $params[] = $this->publish_start->format('Y-m-d H:i:s');
    }

    if (isset($this->publish_end)) {
      $query .= ' AND a.publish_end > ?';
      $params[] = $this->publish_end->format('Y-m-d H:i:s');
    }

    /*
    $temp = $query;
    $i = 0;

    while (($pos = strpos($temp, '?')) !== false) {
      $temp =
        substr($temp, 0, $pos)
        . ((is_numeric($params[$i])) ? $params[$i] : "'" . $params[$i] . "'")
        . substr($temp, $pos + 1);
      $i++;
    }

    die($temp);
    */
    $items = $this->db->getObjects($query, $params);

    if ($items !== false) {
      $this->items = $items;
    }

    return $status;
  }
}
