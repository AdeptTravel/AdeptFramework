<?php

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\Asset
 *
 * Functions to assit with viewing or manipulating the filesystem
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait Asset
{
  public function convertTypeToPath(string $type): string
  {
    $path = strtolower($type);

    switch ($path) {
      case "image":
        $path = 'img';
        break;

      case "javascript":
        $path = 'js';
        break;

      case "text":
        $path = 'txt';
        break;
    }

    return $path;
  }

  public function getFormatInfo(string $extension): object|bool
  {
    $info = (object)[
      'type' => '',
      'mime' => '',
    ];

    $fa = [ //fas or fab
      'Archive' => 'fa-file-archive',
      'Audio' => 'fa-file-audio',
      'CSS' => 'fa-css3-alt',
      'CSV' => 'fa-file-csv',
      'Font' => 'fa-font',
      'HTML' => 'fa-html5',
      'Image' => 'fas fa-image',
      'JSON' => 'fa-js',
      'JavaScript' => 'fa-js',
      'PDF' => 'fa-file-pdf',
      'Text' => 'fa-file-alt',
      'Video' => 'fa-file-video',
      'XML' => 'fa-file-code'
    ];

    // Set type & mime
    switch ($extension) {
      case "css":
        $info->type = "CSS";
        $info->mime = "text/css";
        break;

      case "csv":
        $info->type = "CSV";
        $info->mime = "text/csv";
        break;

      case "eot":
        $info->type = "Font";
        $info->mime = "application/vnd.ms-fontobject";
        break;

      case "gif":
        $info->type = "Image";
        $info->mime = "image/gif";
        break;

      case "html":
        $info->type = "HTML";
        $info->mime = "text/html";
        break;

      case "ico":
        $info->type = "Image";
        $info->mime = "image/vnd.microsoft.icon";
        break;

      case "jpg":
      case "jpeg":
        $info->type = "Image";
        $info->mime = "image/jpeg";
        break;

      case "js":
        $info->type = "JavaScript";
        $info->mime = "text/javascript";
        break;

      case "json":
        $info->type = "JSON";
        $info->mime = "application/json";
        break;

      case "otf":
        $info->type = "Font";
        $info->mime = "font/otf";
        break;

      case "pdf":
        $info->type = "PDF";
        $info->mime = "application/pdf";
        break;

      case "png":
        $info->type = "Image";
        $info->mime = "image/png";
        break;

      case "svg":
        $info->type = "Image";
        $info->mime = "image/svg+xml";
        break;

      case "ttf":
        $info->type = "Font";
        $info->mime = "font/ttf";
        break;

      case "txt":
        $info->type = "Text";
        $info->mime = "text/plain";
        break;

      case "webp":
        $info->type = "Image";
        $info->mime = "image/webp";
        break;

      case "woff":
        $info->type = "Font";
        $info->mime = "font/woff";
        break;

      case "woff2":
        $info->type = "Font";
        $info->mime = "font/woff2";
        break;

      case "xml":
        $info->type = "XML";
        $info->mime = "application/xml";
        break;

      default:
        $info = false;
        break;
    }

    if ($info !== false) {
      $info->fa = $fa[$info->type];
    }


    return $info;
  }
}
