<?php

namespace Adept\Abstract\Configuration\Media;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Properties\Size;

class Image
{
  public array $formats = [
    'jpg',
    'jpeg',
    'png',
    'svg',
    'tif',
    'tiff',
    'webp'
  ];

  public Size $intro;
  public Size $full;
  public Size $thumbnail;
  public Size $editor;

  public function __construct()
  {
    $this->intro      = new Size();
    $this->full       = new Size();
    $this->thumbnail  = new Size();
    $this->editor     = new Size();
  }
}
