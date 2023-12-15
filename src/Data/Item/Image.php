<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die('No Access');

class Image extends \Adept\Abstract\Data\Item\Media
{

  public string $type = 'Image';

  public function save(string $table = ''): bool
  {
    $create = ($this->id == 0);
    $status = parent::save();

    if ($create && $status) {
      $this->create(135, 135);
    }

    return $status;
  }

  protected function isDuplicate(string $table = ''): bool
  {
    $data = [
      'alias' => $this->alias
    ];

    return $this->db->isDuplicate('media', $data);
  }

  public function getUrl(
    int $width,
    int $height,
    int $type = IMAGETYPE_WEBP,
    int $dpi = 72,
    int $quality = 85,
    bool $create = true
  ): string {

    $url = $this->alias . '-' . $width . 'x' . $height . 'dpi' . $dpi;

    switch ($type) {
      case IMAGETYPE_JPEG:
        $url .= 'ql' . $quality . '.jpg';
        break;
      case IMAGETYPE_PNG:
        $url .= '.png';
        break;
      case IMAGETYPE_WEBP:
        $url .= 'ql' . $quality . '.webp';
        break;
      case IMAGETYPE_GIF:
        $url .= '.gif';
        break;
      default:
        break;
    }

    if ($create && !file_exists(FS_IMG . $url)) {
      $this->create($width, $height, $type, $dpi, $quality = 85);
    }

    return $url;
  }

  public function create(
    int $width,
    int $height,
    int $type = IMAGETYPE_WEBP,
    int $dpi = 72,
    int $quality = 85
  ) {
    // Check if GD Library is installed
    //if (!extension_loaded('gd')) {
    //  return 'GD library is not available.';
    //}

    $originalFile = FS_SITE_MEDIA . $this->file;

    $newFile = FS_IMG . $this->getUrl($width, $height, $type, $dpi, $quality = 85, false);

    $dir = substr($newFile, 0, strrpos($newFile, '/'));

    if (!file_exists($dir)) {
      mkdir($dir, 0777, true);
    }
    /*
    $newFile = FS_IMG . $this->alias . '-' . $width . 'x' . $height . 'dpi' . $dpi;

    switch ($type) {
      case IMAGETYPE_JPEG:
        $newFile .= 'ql' . $quality . '.jpg';
        break;
      case IMAGETYPE_PNG:
        $newFile .= '.png';
        break;
      case IMAGETYPE_WEBP:
        $newFile .= 'ql' . $quality . '.webp';
        break;
      case IMAGETYPE_GIF:
        $newFile .= '.gif';
        break;
    }

    if (file_exists($newFile)) {
      //if (filemtime(FS_SITE_MEDIA . $))
    }
    */

    // Intilizie the vairable for scope
    $original = null;
    // Get image type
    $originalType = exif_imagetype($originalFile);


    switch ($originalType) {
      case IMAGETYPE_JPEG:
        $original = imagecreatefromjpeg($originalFile);
        break;
      case IMAGETYPE_PNG:
        $original = imagecreatefrompng($originalFile);
        break;
      case IMAGETYPE_WEBP:
        $original = imagecreatefromwebp($originalFile);
        break;
      case IMAGETYPE_GIF:
        $original = imagecreatefromgif($originalFile);
        break;
      default:
        break;
    }

    if (isset($original)) {
      // Calculate new image dimensions
      list($originalWidth, $originalHeight) = getimagesize($originalFile);
      $ratio = $originalWidth / $originalHeight;

      if ($width / $height > $ratio) {
        $width = round($height * $ratio);
      } else {
        $height = round($width / $ratio);
      }

      // Create a new image with the new dimensions
      $new = imagecreatetruecolor($width, $height);

      // Copy and resize the old image into the new image
      imagecopyresampled($new, $original, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

      // Save the new image

      switch ($type) {
        case IMAGETYPE_JPEG:
          imagejpeg($new, $newFile, $quality); // Save JPEG
          break;
        case IMAGETYPE_PNG:
          imagepng($new, $newFile); // Save PNG
          break;
        case IMAGETYPE_WEBP:
          imagewebp($new, $newFile, $quality); // Save WebP
          break;
        case IMAGETYPE_GIF:
          imagegif($new, $newFile); // Save GIF
          break;
      }

      // Free up memory
      imagedestroy($original);
      imagedestroy($new);
    }
  }
}
