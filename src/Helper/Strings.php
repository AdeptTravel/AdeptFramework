<?php

namespace Adept\Helper;

defined('_ADEPT_INIT') or die();

class Strings
{
  public static function truncate(string $string, int $length)
  {
    // Check if the string is already within the max length
    if (strlen($string) <= $length) {
      return $string;
    }

    // Trim the string to the max length
    $truncated = substr($string, 0, $length);

    // If there's a space, truncate to the last full word
    if (($lastSpace = strrpos($truncated, ' ')) !== false) {
      $truncated = substr($truncated, 0, $lastSpace);
    }

    return $truncated;
  }
}
