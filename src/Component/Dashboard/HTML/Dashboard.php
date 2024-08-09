<?php

namespace Adept\Component\Dashboard\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;
use \Adept\Document\HTML\Head;
use \Adept\Document\HTML\Body\Status;

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

    $app = \Adept\Application::getInstance();
    $app->html->head->meta->title = 'Dashboard';
  }
}
