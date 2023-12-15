<?php

namespace Adept\Component\Travel\User\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;
use \Adept\Data\Item\Location;
use \Adept\Data\Item\Phone;
use \Adept\Data\Item\User;
use \Adept\Document\HTML\Body\Status;

class Edit extends \Adept\Abstract\Component
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

    $post = &$app->session->request->data->post;
    $user = &$app->session->auth->user;

    if ($app->session->request->url->type == 'HTML') {
      $this->status = new Status();
    }

    if (!$post->isEmpty()) {
      $user->loadFromPost($post);

      if ($user->save()) {
        $this->status->addSuccess('Succes', 'User information has been updated.');
      }
    }
  }
}
