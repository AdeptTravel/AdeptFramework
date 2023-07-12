<?php

namespace AdeptCMS\Document\HTML\Body\Element\Select;

defined('_ADEPT_INIT') or die();

class Publish extends \AdeptCMS\Document\HTML\Body\Element\Select
{
  public function getOptions(): array
  {
    return [
      (object)[
        "value" => "0",
        "title" => "Unpublished"
      ],
      (object)[
        "value" => "1",
        "title" => "Published"
      ],
    ];
  }
}
