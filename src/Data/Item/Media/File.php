<?php

namespace Adept\Data\Item;

use DateTime;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Session\Request\Data\Post;
use \Adept\Application\Database;
use \Adept\Data\Item\Route;
use \Adept\Data\Item\User;

class Content extends \Adept\Abstract\Data\Item
{
  protected string $name = 'Content';
  protected string $table = 'content';

  /**
   * Undocumented variable
   *
   * @var /Adept/Item/Content
   */
  public Content $parent;

  /**
   * Undocumented variable
   *
   * @var \Adept\Data\Item\Route
   */
  public Route $route;

  /**
   * Undocumented variable
   *
   * @var \Adept\Data\Item\User
   */
  public User $author;

  public int $version = 0;
  public string $type;
  public string $subtype = '';
  public string $title = '';
  public string $summary = '';
  public string $content = '';

  public array $articles = [];
  public array $categories = [];
  public array $tags = [];

  public object $seo;
  public object $params;
  public int $status;
  public int $order;

  public \DateTime $created;
  public \DateTime $modified;
  public \DateTime $publish;
  public \DateTime $unpublish;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  int                         $id
   */
  public function __construct(Database $db, int $id = 0)
  {
    $this->excludeKeys = ['items'];

    $this->excludeKeysOnNew = [
      'created',
      'modified',
      'publish',
      'unpublish'
    ];

    parent::__construct($db, $id);
  }

  public function load(int|string $id, string $col = 'id'): bool
  {
    $status = parent::load($id, $col);

    if ($this->type == 'Article') {
      //
    } else if ($this->type == 'Category') {
      $this->articles = $this->getChildren('Article');
      $this->categories = $this->getChildren('Category');
    } else if ($this->type == 'Tag') {
      $this->articles = $this->getChildren('Article');
      $this->tags = $this->getChildren('Tag');
    }

    return $status;
  }

  public function save(string $table = ''): bool
  {
    if (!isset($this->route->id) || $this->route->id == 0) {
      $route = '';

      if (isset($this->parent)) {
        $route = $this->parent->route->route . '/';
      }

      $route .= $this->createAlias();

      $this->route = new Route($this->db);
      $this->route->route = $route;
      $this->route->component = 'Content';
      $this->route->option = $this->type;

      if (!isset($this->parent)) {
        die(print_r($this->route));
      }
    }

    // TODO: Save to version
    parent::save($this->table . '_version');

    // Increment the version number
    $this->version++;

    return parent::save();
  }

  protected function isDuplicate(string $table = ''): bool
  {
    $isDup = false;

    if ($this->id == 0) {
      if ($this->route->id > 0) {
        $count = $this->db->getInt(
          'SELECT COUNT(*) FROM `content` WHERE `route` = ?',
          [$this->route->route]
        );

        $isDup = ($count > 0);
      }

      if (!$isDup) {
        if (!isset($this->parent)) {
          die(print_r($this, true));
        }

        $count = $this->db->getInt(
          "SELECT COUNT(*) FROM `content` WHERE `parent` = ? AND `title` = ?",
          [$this->parent->id, $this->title]
        );

        $isDup = ($count > 0);
      }
    }

    return $isDup;
  }

  protected function getChildren(string $type = 'Article'): array
  {
    $query  = 'SELECT a.id, b.route, c.title AS `parent`, d.route AS `parent_route`, a.title,';
    $query .= ' a.type, a.subtype, a.summary, a.seo, a.params, a.status, a.publish_start,';
    $query .= ' a.publish_end, a.created, a.modified, a.order';
    $query .= ' FROM content AS a ';
    $query .= ' INNER JOIN route AS b ON a.route = b.id';
    $query .= ' LEFT JOIN content AS c on a.parent = c.id ';
    $query .= ' LEFT JOIN route AS d ON c.route = d.id';
    $query .= " WHERE a.type = ? AND a.parent = ?";

    $params = [
      $type,
      $this->id
    ];

    $items = $this->db->getObjects($query, $params);

    return ($items !== false) ? $items : [];
  }

  public function createAlias(): string
  {
    $alias = strtolower($this->title);
    $alias = preg_replace('/[^0-9a-z-]/', '-', $alias);
    $alias = str_replace('--', '-', $alias);

    $parts = explode('-', $alias);
    $count = count($parts);

    for ($i = 0; $i < $count; $i++) {
      if (empty($parts[$i])) {
        unset($parts[$i]);
      }
    }

    $alias = implode('-', $parts);

    return $alias;
  }
}
