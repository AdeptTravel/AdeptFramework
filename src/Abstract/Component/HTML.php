<?php

namespace Adept\Abstract\Component;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Abstract\Configuration\Component;
use Adept\Application;
use Adept\Document\HTML\Head;
use Adept\Document\HTML\Body\Status;

class HTML extends \Adept\Abstract\Component
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

  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
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
    $request    = &$app->session->request;
    $component  = $request->route->component;
    $option     = $request->route->view;
    $type       = $request->url->type;

    if (!empty($template)) {
      $template = '.' . $template;
    }

    if (
      file_exists($file = FS_CORE_COMPONENT . "$component/$type/Template/$option$template.php")
      || file_exists($file = FS_SITE_COMPONENT . "$component/$type/Template/$option$template.php")
    ) {
      ob_start();

      include($file);

      $buffer = ob_get_contents();

      ob_end_clean();
    }

    return $buffer;
  }
}
