<?php

/**
 * \Adept\Data\Item\Module
 *
 * The module data item
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

/**
 * \Adept\Data\Item\Module
 *
 * The module data item
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Module extends \Adept\Abstract\Data\Item
{

  protected string $errorName = 'Module';
  protected string $table = 'module';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $title;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $path;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $area;

  /**
   * Undocumented variable
   *
   * @var object
   */
  public object $conf;

  /**
   * Undocumented variable
   *
   * @var bool
   */
  public bool $status;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $order;
}
