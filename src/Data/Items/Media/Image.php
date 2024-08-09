<?php

namespace Adept\Data\Items\Media;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Image extends \Adept\Data\Items\Media
{
  protected string $table    = 'Media';
  protected string $indexCol = 'file';

  public string $type = 'Image';
}
