<?php

namespace Adept\Component\Route\JSON;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Data\Item\Route;
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

    $id = $app->session->request->data->post->getInt('id', 0);
    $redirect = $app->session->request->data->post->getString('redirect', '');
    $action = $app->session->request->data->post->getString('action', '');
    $result = false;

    switch ($action) {

      case 'save':
        if ($id > 0 && !empty($redirect)) {
          $item = new Route($this->app->db, $id);
          $item->redirect = $redirect;
          $result = $item->save();
        }

        break;

      case 'publish':
        break;

      case 'unpublish':
        break;

      default:
        break;
    }

    $json = 
  }
}
