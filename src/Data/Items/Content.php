<?php

namespace AdeptCMS\Data\Items;

defined('_ADEPT_INIT') or die();

class Content extends \AdeptCMS\Base\Data\Items
{
  public function __construct(
    \AdeptCMS\Application\Database &$db,
    int|string $id = 0,
    array $filter = []
  ) {
    parent::__construct($db, $id, $filter);
  }
}
