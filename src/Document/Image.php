<?php

namespace AdeptCMS\Document;

defined('_ADEPT_INIT') or die();

class Image extends \AdeptCMS\Base\Document
{
  protected $extension;

  public function getBuffer(): string
  {
    $buffer = '';
    $request = $this->app->session->request;
    $size = getimagesize($this->file);

    $this->extension = $request->url->extension;

    $width = $request->data->getInt('width', INPUT_GET);
    $height = $request->data->getInt('height', INPUT_GET);

    if (!empty($width) || !empty($height)) {
      if (!empty($width) && empty($height)) {
        $height = round(($size[1] / $size[0]) * $width);
      } else if (empty($width) && !empty($height)) {
        $width = round(($size[0] / $size[1]) * $height);
      }

      $cache = $this->file . '/' . $width . 'x' . $height . '.' . $this->extension;
      $cache = str_replace(FS_PATH, FS_CACHE, $cache);

      if (!file_exists($cache)) {
        $image = $this->getImage($this->file);
        $image = $this->resizeImage($image, $width, $height);
        $this->saveImage($image, $cache);
      }

      $buffer = file_get_contents($cache);
    } else {
      $buffer = file_get_contents($this->file);
    }

    return $buffer;
  }

  public function getImage(string $file): \GdImage|null
  {
    $image = null;

    switch ($this->extension) {
      case 'png':
        $image = imagecreatefrompng($file);
        break;

      case 'jpeg':
      case 'jpg':
        $image = imagecreatefromjpeg($file);
        break;

      case 'webp':
        $image = imagecreatefromwebp($file);
        break;

      case 'gif':
        $image = imagecreatefromgif($file);
        break;

      default:
        break;
    }

    return $image;
  }

  public function resizeImage(\GdImage $image, int $width, int $height): \GdImage|bool
  {
    $resized = imagecreatetruecolor($width, $height);

    if ($this->extension == 'gif') {

      $transparentIndex = imagecolortransparent($image);

      // If we have a specific transparent color
      if ($transparentIndex >= 0) {
        // Get the original image's transparent color's RGB values
        $tColor = imagecolorsforindex($image, $transparentIndex);
        // Allocate the same color in the new image resource
        $transparentIndex = imagecolorallocate($resized, $tColor['red'], $tColor['green'], $tColor['blue']);
        // Completely fill the background of the new image with allocated color.
        imagefill($resized, 0, 0, $transparentIndex);
        // Set the background color for new image to transparent
        imagecolortransparent($resized, $transparentIndex);
      }
    } else if ($this->extension == 'png') {
      // These parameters are required for handling PNG files.
      imagealphablending($resized, false);
      imagesavealpha($resized, true);
      $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
      imagefilledrectangle($resized, 0, 0, $width, $height, $transparent);
    }

    $status = imagecopyresampled(
      $resized,        // Resized image
      $image,          // Original image
      0,               // New X
      0,               // New Y
      0,               // Original X
      0,               // Original Y
      $width,          // New width
      $height,         // New height
      imagesx($image), // Original width
      imagesy($image)  // Original height
    );

    return ($status) ? $resized : false;
  }

  public function saveImage(\GdImage $image, string $file, int $compression = 100): bool
  {
    $status = false;
    $type = substr($file, strrpos($file, '.') + 1);

    $path = substr($file, 0, strrpos($file, '/'));

    if (!file_exists($path)) {
      mkdir($path, 0755, true);
    }

    switch ($type) {

      case 'png':
        $status = imagepng($image, $file, $compression);
        break;

      case 'jpg':
      case 'jpeg';
        $status = imagejpeg($image, $file, $compression);
        break;

      case 'webp':
        $status = imagewebp($image, $file, $compression);
        break;

      case 'gif':
        $status = imagegif($image, $file);
        break;
    }

    return $status;
  }
}
