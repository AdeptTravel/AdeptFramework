<?php

namespace Adept\Component\Core\Auth\Global\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;
use \Adept\Document\HTML\Head;
use \Adept\Document\HTML\Body\Status;

class Login extends \Adept\Abstract\Component\HTML
{
  /**
   * Init
   */
  public function __construct()
  {
    parent::__construct();

    // Shortcut
    $app     = \Adept\Application::getInstance();
    $auth    = $app->session->auth;
    $request = $app->session->request;
    $get     = $request->data->get;
    $post    = $request->data->post;

    $this->status = new Status();

    if (!$auth->status && !$post->isEmpty()) {

      $username = $post->getEmail('email', '', 254);
      $password = $post->get('password', '', 32);

      if (!empty($username) && !empty($password)) {

        if ($auth->login($username, $password)) {

          $this->status->addSuccess('Success', 'You are now logged in!');

          $request->redirect(
            $get->getString('redirect', '/'),
            false
          );
        } else {
          //$d = $this->app->session->auth->delay;
          $now = new \DateTime();
          $diff = abs($now->getTimestamp() - $auth->delay->getTimestamp());

          $h = floor($diff / 3600);
          $m = floor(floor($diff / 60) % 60);
          $s = $diff % 60;
          $msg = 'You must wait';


          if ($h > 0) {
            $msg .= " $h Hour";

            if ($h > 1) $msg .= 's';

            if ($m > 0 || $s > 0) {
              $msg .= ',';
            }
          }

          if ($m > 0) {
            $msg .= " $m Minute";

            if ($m > 1) $msg .= 's';

            if ($s > 0) {
              $msg .= ',';
            }
          }

          if ($s > 0) {
            $msg .= " $s Second";

            if ($s > 1) $msg .= 's';
          }

          $this->status->addError('Error', 'There was an error logging in.');
          //$this->status->addInformation('Login Delay', "You must wait $m Minutes $s Seconds to try again.");
          $this->status->addInformation('Login Delay', $msg);
        }
      }
    }
  }

  public static function getBreadcrumbTitle(string $area, string $view, array $args = []): string
  {
    return 'Login';
  }
}
