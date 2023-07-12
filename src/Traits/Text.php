<?php

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\Text
 *
 * 
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait Text
{

  public function trimToSpace(string $text, int $length, string $continue = '...'): string
  {
    if (strlen($text) > $length) {
      $text = str_replace(["\n", "\r"], ' ', $text);
      $text = str_replace('  ', ' ', $text);
      $text = trim($text);

      $length -= strlen($continue);

      if (strpos($text, ' ') === false) {
        $text = substr($text, 0, $length) . $continue;
      } else {
        $text = substr($text, 0, strrpos($text, ' ', $length));
      }
    }

    return $text;
  }
}
