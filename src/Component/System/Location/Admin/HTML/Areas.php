<?php

namespace Adept\Component\System\Location\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Areas extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Currencies';

    // Component controls
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getTable(): \Adept\Data\Table\Location\Area
  {
    $get   = Application::getInstance()->session->request->data->get;
    $table = new \Adept\Data\Table\Location\Area();

    if (!empty($country = $get->getInt('country'))) {
      $table->countryId = $country;
    }



    //$table->sort = $get->getString('sort', 'country');
    //$table->dir  = $get->getString('dir', 'asc');

    return $table;
  }
}
