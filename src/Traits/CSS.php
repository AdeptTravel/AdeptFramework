<?php

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\CSS
 *
 * Functions to assit with CSS files
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait CSS
{
  public function minify(string $file): string
  {
    $parser = new \Sabberworm\CSS\Parser(file_get_contents($file));
    $document = $parser->parse();
    return $document->render(\Sabberworm\CSS\OutputFormat::createCompact());
  }
}
