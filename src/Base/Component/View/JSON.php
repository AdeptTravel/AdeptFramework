<?php

namespace AdeptCMS\Base\Component\View;

defined('_ADEPT_INIT') or die('No Access');

class JSON extends \AdeptCMS\Base\Component\View
{
  public function getBuffer(): string
  {
    return $this->model->getJSON();
  }
}
