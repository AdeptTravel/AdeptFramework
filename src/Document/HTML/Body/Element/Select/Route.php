<?php

namespace AdeptCMS\Document\HTML\Body\Element\Select;

defined('_ADEPT_INIT') or die();

class Route extends \AdeptCMS\Document\HTML\Body\Element\Select
{
  public function getOptions(): array
  {
    $result = $this->app->getDB()->getObjects(
      "SELECT `id` AS `value`, `route` AS `title` FROM `route` ORDER BY `route` ASC",
      []
    );

    // Add first element
    array_unshift($result, (object)[
      'value' => 0,
      'title' => '-- Select Route --',
    ]);

    return $result;
  }
}
