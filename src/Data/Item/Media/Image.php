<?php

namespace Adept\Data\Item\Media;

defined('_ADEPT_INIT') or die('No Access');

class Image extends \Adept\Data\Item\Media
{

  protected string $table = 'Media';
  public string $type = 'Image';

  public function getRelPath(
    int $width,
    int $height,
    int $type = IMAGETYPE_WEBP,
    int $dpi = 72,
    int $quality = 85,
  ): string {
    $abs = $this->getAbsFile($width, $height, $type, $dpi, $quality);

    if (!file_exists($abs)) {
      $this->create($width, $height, $type, $dpi, $quality);
    }

    return str_replace(FS_SITE, '', $abs);
  }

  public function getAbsFile(
    int $width,
    int $height,
    int $type = IMAGETYPE_WEBP,
    int $dpi = 72,
    int $quality = 85,
  ): string {

    $path = FS_IMG . $this->alias . '-' . $width . 'x' . $height;

    switch ($type) {
      case IMAGETYPE_JPEG:
        $path .= '.jpg';
        //$newFile .= 'x' . $quality . '.jpg';
        break;

      case IMAGETYPE_PNG:
        $path .= '.png';
        //$newFile .= 'x' . $quality . '.png';
        break;

      case IMAGETYPE_WEBP:
        $path .= '.webp';
        break;

      case IMAGETYPE_GIF:
        $path .= '.gif';
        break;

      default:
        break;
    }

    return $path;
  }

  public function create(
    int $width,
    int $height,
    int $type = IMAGETYPE_WEBP,
    int $dpi = 72,
    int $quality = 85,
    bool $overwrite = false
  ) {
    // Check if GD Library is installed
    //if (!extension_loaded('gd')) {
    //  return 'GD library is not available.';
    //}

    $orgFile = FS_SITE_MEDIA . $this->type . $this->file;
    $newFile = $this->getAbsFile($width, $height, $type, $dpi, $quality);

    if ($overwrite || !file_exists($newFile)) {
      $dir = substr($newFile, 0, strrpos($newFile, '/'));

      if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
      }

      // Intilizie the vairable for scope
      $original = null;
      // Get image type
      $originalType = exif_imagetype($orgFile);

      switch ($originalType) {
        case IMAGETYPE_JPEG:
          $original = imagecreatefromjpeg($orgFile);
          break;
        case IMAGETYPE_PNG:
          $original = imagecreatefrompng($orgFile);
          break;
        case IMAGETYPE_WEBP:
          $original = imagecreatefromwebp($orgFile);
          break;
        case IMAGETYPE_GIF:
          $original = imagecreatefromgif($orgFile);
          break;
        default:
          break;
      }

      if (isset($original)) {
        // Calculate new image dimensions
        list($originalWidth, $originalHeight) = getimagesize($orgFile);
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
}
