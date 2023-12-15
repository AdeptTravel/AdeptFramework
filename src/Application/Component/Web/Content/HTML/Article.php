<?php

namespace Adept\Component\Content;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Data\Item\Content;


class Article extends \Adept\Abstract\Component
{
  /**
   * Undocumented function
   *
   * @param  \Adept\Application         $app
   * @param    \Adept\Abstract\Document  $doc
   */
  public function __construct(Application &$app, Document &$doc)
  {
    parent::__construct($app, $doc);

    $item = new Content($app->db);
    $item->load($app->session->request->route->id, 'route');

    $this->item = $item;
  }
}
