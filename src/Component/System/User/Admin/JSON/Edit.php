<?php

namespace Adept\Component\User\JSON;

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

    $this->data = (object) [
      'status' => 'error',
      'title' => 'Error',
      'message' => 'There was an error saving your changes.'
    ];

    if (
      $post->getInt('id') == $user->id
      && (!empty($key = $post->getString('name')))
    ) {
      if ($key == 'email') {
        $key = 'username';
      }

      $user->$key = $post->getString('value');
      if ($user->save()) {
        $this->data = (object) [
          'status' => 'success',
          'title' => 'Saved',
          'message' => 'Your changes have been saved.'
        ];
      }
    }
  }
}
