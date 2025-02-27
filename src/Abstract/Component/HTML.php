<?php

namespace Adept\Abstract\Component;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Abstract\Configuration\Component;
use Adept\Application;
use Adept\Document\HTML\Head;
use Adept\Document\HTML\Body\Status;

abstract class HTML extends \Adept\Abstract\Component
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Component
   */
  public \Adept\Abstract\Configuration\Component $conf;

  /**
   * Status messages
   *
   * @var \Adept\Document\HTML\Body\Status
   */
  public Status $status;

  public function __construct()
  {
    $app = Application::getInstance();

    $this->conf = $app->conf->component;
    $this->status = new Status();
  }

  // This is here for maybe loading sub-templates from a template file.  Might
  // just get rid of it, not sure yet.
  public function getHTML(string $template): string
  {

    $app        = Application::getInstance();
    $buffer     = '';

    if (!empty($template)) {
      $template = '.' . $template;
    }

    $path =
      $app->session->request->route->type . '/' .
      $app->session->request->route->component . '/' .
      $app->session->request->route->area . '/' .
      $app->session->request->url->type . '/' .
      'Template/' .
      $app->session->request->route->view . $template . '.php';

    if (
      file_exists($file = FS_CORE_COMPONENT . $path)
      || file_exists($file = FS_SITE_COMPONENT . $path)
    ) {
      ob_start();

      include($file);

      $buffer = ob_get_contents();

      ob_end_clean();
    }

    return $buffer;
  }

  //abstract public static function getBreadcrumbTitle(string $area, string $view, array $args = []): string;
}
