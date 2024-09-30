<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die();

class Request extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Request';

  public int $sessionId;
  public int $ipAddressId;
  public int $useragentId;
  public int $routeId;
  public int $redirectId;
  public int $urlId;
  public int $code;
  public string $status = 'Allow';

  protected function duplicate(): int|bool
  {
    return false;
  }
}
