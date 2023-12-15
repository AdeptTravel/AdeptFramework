<?php

namespace Component\Core\Route\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Document\HTML\Body\Status;

class Redirect extends \Adept\Abstract\Component
{
  /**
   * Undocumented function
   *
   * @param  \Adept\Application         $app
   * @param   \Adept\Abstract\Document  $doc
   */
  public function __construct(Application &$app, Document &$doc)
  {
    parent::__construct($app, $doc);
    $redirect = new \Adept\Data\Items\Redirect($app->db);
    $redirect->load();

    $this->data = $redirect->items;
  }
}
