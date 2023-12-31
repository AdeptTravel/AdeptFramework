<?php

namespace Adept\Component\Core\Auth\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;

class Login extends \Adept\Abstract\Component
{
  public function __construct(Application &$app, Document &$doc)
  {
    parent::__construct($app, $doc);

    $data = $app->session->request->post;

    //$this->view->status->addError('Login Error', 'Username or Password is incorrect');
    //$this->view->status->addWarning('Warning', 'This is a warning');
    //$this->view->status->addInformation('Information', 'This is some info');
    //$this->view->status->addSuccess('Success', 'This is a success message');

    if (!$data->isEmpty()) {
      $username = $data->getEmail('email', '');
      $password = $data->getRaw('password', '');

      if (!empty($username) && !empty($password)) {
        $auth = new Authentication($app->db, $app->session->data);
        if (!$auth->login($username, $password)) {
          $this->view->status->addError('Login Error', 'Username or Password is incorrect');
        }
      }
    }
  }
}
