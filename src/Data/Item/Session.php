<?php

namespace Adept\Data\Item;

use Adept\Application\Database;
use WhichBrowser\Parser;

defined('_ADEPT_INIT') or die();

class Session extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Session';
  protected string $index = 'token';

  public int|null $userId;
  public string $token;
  public string $status = 'Active';

  protected function getData(bool $sql = true): object
  {
    $data = parent::getData($sql);

    if (isset($data->userId) == 0) {
      unset($data->userId);
    }

    return $data;
  }
}
