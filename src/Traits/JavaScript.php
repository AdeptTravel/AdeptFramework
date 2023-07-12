<?php

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\JavaScript
 *
 * Functions to assit with JavaScript files
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait JavaScript
{
  public function minify(string $js): string
  {
    // Remove preceeding and trailing spaces
    $js = trim($js);

    // Nomalize linebreaks
    str_replace(["\r\n", "\r"], "\n", $js);

    // Remove comments
    $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';
    $js = preg_replace($pattern, '', $js);

    // Minify 
    $minifier = new \MatthiasMullie\Minify\JS();
    $minifier->add($js);
    $js = $minifier->minify();

    // Final trim
    $js = trim($js);

    return $js;
  }
}
