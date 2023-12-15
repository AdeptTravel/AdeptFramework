<?php

namespace Component\Core\Auth\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;
use \Adept\Document\HTML\Body\Status;

class Login extends \Adept\Abstract\Component
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Session\Authentication
   */
  public Authentication $auth;

  public function __construct(Application &$app, Document &$doc)
  {
    parent::__construct($app, $doc);

    $this->status = new Status();

    if (!$this->app->session->auth->status && !$this->app->session->request->data->post->isEmpty()) {
      $username = $this->app->session->request->data->post->getEmail('email', '', 254);
      $password = $this->app->session->request->data->post->getRaw('password', '', 32);

      if (!empty($username) && !empty($password)) {
        if ($app->session->auth->login($username, $password)) {
          $this->status->addSuccess('Success', 'You are now logged in!');
          $app->session->request->redirect(
            $app->session->request->data->get->getString('redirect', '/'),
            false
          );
        } else {
          $this->status->addError('Error', 'Username or Password is incorrect');
        }
      }
    }
  }
}
