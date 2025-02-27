<?php

namespace Adept\Helper;

defined('_ADEPT_INIT') or die();

class Image
{
  public static function getRelPath(
    string $alias,
    int $width,
    int $height,
    int $type = IMAGETYPE_WEBP,
    int $dpi = 72,
    int $quality = 85,
  ): string {

    $abs = Image::getAbsFile($alias, $width, $height, $type, $dpi, $quality);

    return str_replace(FS_SITE, '', $abs);
  }

  public static function getAbsFile(
    string $alias,
    int $width,
    int $height,
    int $type = IMAGETYPE_WEBP,
    int $dpi = 72,
    int $quality = 85,
  ): string {

    $path = FS_IMG . $alias . '-w' . $width . 'h' . $height . 'd' . $dpi;

    if ($type != IMAGETYPE_GIF) {
      $path .= 'q' . $quality;
    }

    switch ($type) {
      case IMAGETYPE_JPEG:
        $path .= '.jpg';
        break;

      case IMAGETYPE_PNG:
        $path .= '.png';
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

  public static function create(
    string $file,
    string $alias,
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

    $orgFile = FS_SITE_MEDIA . 'Image' . $file;
    $newFile = Image::getAbsFile($alias, $width, $height, $type, $dpi, $quality);

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

  public static function purge(string $alias)
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
  public static function getDimensions(string $file): object
  {
    $info = getimagesize($file);

    return (object) [
      'width'  => $info[0],
      'height' => $info[1],
      'duration' => 0
    ];
  }
}
