<?php

namespace Adept\Data\Table\Media;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

class Image extends \Adept\Data\Table\Media
{
  protected string $table = 'Media';
  public string $type = 'Image';
}
