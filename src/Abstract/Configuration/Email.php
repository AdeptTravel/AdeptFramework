<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Email\From;

class Email
{
  public bool $debug = false;
  public string $host;
  public string $username;
  public string $password;

  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Email\From
   */

  public From $from;

  public bool $html = true;

  public string $template = 'Email';

  public function __construct()
  {
    $this->from = new From();
  }
}
