<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Content extends \Adept\Abstract\Data\Items
{
  public int       $parent;
  public int       $route;
  public int       $version;
  public string    $type = '';
  public string    $subtype = '';
  public string    $title;
  public string    $summary = '';
  public int       $image = 0;
  public string    $content;
  public object    $seo;
  public object    $media;
  public object    $params;
  public int       $status;
  public int       $order;
  public string    $publish;
  public string    $archive;
  public string    $created;
  public string    $modified;
}
