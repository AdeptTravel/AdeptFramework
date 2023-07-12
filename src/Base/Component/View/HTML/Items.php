<?php

namespace AdeptCMS\Base\Component\View\HTML;

defined('_ADEPT_INIT') or die('No Access');

class Items extends \AdeptCMS\Base\Component\View\HTML
{
  public function onRenderStart()
  {
    $this->addCSSFile('/admin/css/items.css');
    parent::onRenderStart();
  }
}
