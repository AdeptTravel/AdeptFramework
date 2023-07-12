<?php

namespace AdeptCMS\Base\Component\View\HTML;

defined('_ADEPT_INIT') or die('No Access');

abstract class Item extends \AdeptCMS\Base\Component\View\HTML
{
  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Base\Component\Model\Item
   */
  public $item;

  public function onRenderStart()
  {
    parent::onRenderStart();

    $this->addCSSFile('/admin/css/form.css');
    $this->addCSSFile('/admin/css/item.css');
    //$this->addCSSFile('/admin/css/form/asset.css');
    //$this->addCSSFile('/admin/css/form/element.css');

    //$this->addJavaScriptFile('/admin/js/form.js');
    //$this->addJavaScriptFile('/admin/js/mf-conditional-fields.js');
  }
}
