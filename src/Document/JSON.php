<?php

/**
 * \Adept\Document\JSON
 *
 * The document object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Document;

defined('_ADEPT_INIT') or die();

use Adept\Application;

/**
 * \Adept\Document\JSON
 *
 * The document object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class JSON extends \Adept\Abstract\Document
{
  public function getBuffer(): string
  {
    return $this->component->getBuffer();
  }
}
