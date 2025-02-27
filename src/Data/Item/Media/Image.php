<?php

namespace Adept\Data\Item\Media;

use Adept\Application;
use Adept\Helper\Image as Helper;

defined('_ADEPT_INIT') or die('No Access');

class Image extends \Adept\Data\Item\Media
{
  public string $type  = 'Image';

  public function loadInfo()
  {
    parent::loadInfo();



    // If the file has changed purge all the optimized versions of the image
    if ($this->hasChanged()) {
      $this->purge($this->alias);
    }

    // Create thumbnails if they don't already exist

    $conf = Application::getInstance()->conf->media->image;

    foreach (['thumbnail', 'editor'] as $thumbs) {
      if (!file_exists(Helper::getAbsFile(
        $this->alias,
        $conf->$thumbs->width,
        $conf->$thumbs->height,
        IMAGETYPE_WEBP,
        $conf->$thumbs->dpi,
        $conf->$thumbs->quality
      ))) {

        Helper::create(
          $this->file,
          $this->alias,
          $conf->$thumbs->width,
          $conf->$thumbs->height,
          IMAGETYPE_WEBP,
          $conf->$thumbs->dpi,
          $conf->$thumbs->quality
        );
      }
    }
  }

  public function purge(string $alias)
  {
    $pattern = FS_IMG . $alias . '*x*.webp';

    // Use glob() to find files matching the pattern
    $files = glob($pattern);

    for ($i = 0; $i < count($files); $i++) {
      // Extract the filename part after the prefix
      $part = str_replace(FS_IMG . $alias . '-', '', $files[$i]);
      // Check if the filename part matches the pattern for dimensions only (e.g., 150x150)
      if (preg_match('/^\d+x\d+\.webp$/', $part)) {
        unlink($files[$i]);
      }
    }
  }
  protected function getDimensions(string $file): object
  {
    $info = getimagesize($file);

    return (object) [
      'width'  => $info[0],
      'height' => $info[1],
      'duration' => 0
    ];
  }
}
