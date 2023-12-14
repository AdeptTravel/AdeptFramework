<?php

/**
 * \Adept\Application\Session\Request\Data
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Application\Session\Request;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Session\Request\Data\Get;
use \Adept\Application\Session\Request\Data\Post;
use \Adept\Application\Session\Request\Route;

/**
 * \Adept\Application\Session\Data
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class Data
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Session\Request\Data\Get
   */
  public \Adept\Application\Session\Request\Data\Get $get;

  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Session\Request\Data\Post
   */
  public \Adept\Application\Session\Request\Data\Post $post;

  /**
   * @param  \Adept\Application\Session\Request\Route $route
   */
  public function __construct()
  {
    $this->get = new Get();
    $this->post = new Post();
  }
}
