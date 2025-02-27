<?php

namespace Adept\Component\System\Dashboard\Admin\HTML;
//namespace Adept\Component\Core\Auth\Global\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application;

class Dashboard extends \Adept\Abstract\Component\HTML
{
  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct()
  {
    parent::__construct();

    $app = Application::getInstance();
    $app->html->head->meta->title = 'Dashboard';
  }
}
