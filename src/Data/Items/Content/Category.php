<?php

namespace Adept\Data\Items\Content;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Category extends \Adept\Data\Items\Content
{
  protected string $table = 'Content';

  public string $type = 'Category';
  public bool $recursive = true;
}
