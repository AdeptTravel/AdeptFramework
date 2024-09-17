<?php

namespace Adept\Data\Item;

use Adept\Application\Database;
use WhichBrowser\Parser;

defined('_ADEPT_INIT') or die();

class Request extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Request';

  public int $session;
  public int $ipaddress;
  public int $useragent;
  public int $route;
  public int $url;
  public int $code;
  public bool $block;
  public \DateTime $created;
  public int $milisec;
}
