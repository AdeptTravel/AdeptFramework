<?php

namespace Adept\Data\Items;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Image extends \Adept\Abstract\Data\Items\Media
{
  public string $type = 'Image';
  public string $sort = 'title';
}
