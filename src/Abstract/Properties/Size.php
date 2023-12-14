<?php

/**
 * \Adept\Abstract\GetVars
 *
 * Get's variable from Get or Post
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Abstract\Properties;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Abstract\Properties\Size
 *
 * 
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Size
{
    /**
     * Undocumented variable
     *
     * @var int
     */
    public int $width = 0;

    /**
     * Undocumented variable
     *
     * @var int
     */
    public int $height = 0;

    /**
     * For file formats that support it (eg. WebP, JPG, etc.)
     *
     * @var int
     */
    public int $compress = 0;
}
