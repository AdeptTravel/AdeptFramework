<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Content extends \Adept\Abstract\Data\Table
{
  protected string $table = 'Content';

  protected array $like = ['title'];

  protected array $joinLeft = [
    'Route'   => 'routeId',
    'User'    => 'userId',
    'Content Parent' => 'parentId'
  ];

  protected array $recursiveSort = [
    'title',
    'sortOrder'
  ];

  public string $sort = 'title';

  public int    $parentId;
  public int    $routeId;
  public int    $imageId;
  public int    $userId;
  public string $type; //ENUM('Article', 'Category', 'Component', 'Tag')
  public string $subtype; //ENUM('', 'Blog', 'News', 'Video') DEFAULT '',
  public string $title;
  public string $summary;
  public string $content;
  public string $metaSummary;
  public string $metaDescription;
  public string $ogTitle;
  public string $ogDescription;
  public string $ogLocale;
  public int    $ogImageId;
  public string $xTitle;
  public string $xDescription;
  public int    $xImageId;
  public string $xCardType; //ENUM('summary', 'summary_large_image', 'app', 'player')
  public object $params;
  public string $activeOn;
  public string $archiveOn;
  public int    $sortOrder;

  //`status` ENUM('Active', 'Archive', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',

  public function getItem(int $id = 0): \Adept\Data\Item\Content
  {
    $item = new \Adept\Data\Item\Content();

    if ($id > 0) {
      $item->loadFromId($id);
    }

    return $item;
  }
}
