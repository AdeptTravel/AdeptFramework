<?php

namespace Adept\Data\Item;

use Adept\Application\Database;
use WhichBrowser\Parser;

defined('_ADEPT_INIT') or die();

class Session extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Session';
  protected string $index = 'token';

  public int $user;
  public string $token;
  public bool $block;
  public \DateTime $created;
}
