<?php

namespace Adept\Component\Auth\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;

class Login extends \Adept\Abstract\Component
{
  public function __construct()
  {
    parent::__construct();

    $app  = Application::getInstance();
    $auth = $app->session->auth;
    $data = $app->session->request->data->post;

    //$this->view->status->addError('Login Error', 'Username or Password is incorrect');
    //$this->view->status->addWarning('Warning', 'This is a warning');
    //$this->view->status->addInformation('Information', 'This is some info');
    //$this->view->status->addSuccess('Success', 'This is a success message');

    if (!$data->isEmpty()) {
      $username = $data->getEmail('email', '');
      $password = $data->get('password', '');

      if (!empty($username) && !empty($password)) {


        if (!$auth->login($username, $password)) {
          $this->view->status->addError('Login Error', 'Username or Password is incorrect');
        }
      }
    }
  }
}
