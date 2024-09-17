<?php

namespace Adept\Data\Table\Content;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Category extends \Adept\Data\Table\Content
{
  protected string $table = 'Content';

  public string $type = 'Category';
  public bool $recursive = true;
}
