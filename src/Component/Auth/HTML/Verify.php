<?php

namespace Adept\Component\Auth\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;
use \Adept\Data\Item\User;
use \Adept\Document\HTML\Body\Status;

class Verify extends \Adept\Abstract\Component
{
  public string $action = 'verify';
  public string $token = '';

  public function __construct(Application &$app, Document &$doc)
  {
    parent::__construct($app, $doc);

    $this->status = new Status();

    $auth = &$this->app->session->auth;
    $get  = &$this->app->session->request->data->get;
    $post = &$this->app->session->request->data->post;

    // No POST data, just a token through GET
    if (
      $post->isEmpty()
      && !$get->isEmpty()
      && !empty($token = $get->getAlphaNumeric('token', ''))
      && strlen($token) == 32
    ) {

      // First step, token has been recieved, display form
      $this->token = $token;
      // Verify token is valid
      if ($auth->tokenExists('Verify', $token)) {
        // Show first form
        $this->action = 'verify';
      } else {

        $this->action = 'error';
        $this->status->addError('Token Expired', 'The token has expired, please submit information to have token resent.');
      }
    }
    // No GET data, just POST with token which means verify
    else if (
      $get->isEmpty()
      && !$post->isEmpty()
      && !empty($token = $post->getAlphaNumeric('token'))
      && strlen($token) == 32
    ) {

      $this->token = $token;

      $email = $post->getEmail('email');
      $password = $post->get('password', '');
      //$dob = $post->getDate('dob');

      // Check inputs
      //if (empty($email) || empty($password) || !isset($dob)) {
      if (empty($email) || empty($password)) {
        $this->action = 'verify';
        $this->status->addError('Error', 'There was an error with either the Email Address, password, or your Date of Birth.');
      } else {

        //if ($auth->tokenCheck('Verify', $token, $email, $password, $dob)) {
        if ($auth->tokenCheck('Verify', $token, $email, $password)) {
          $auth->user->verified = new \DateTime();
          $auth->user->status = 1;
          $auth->user->save();

          $this->status->addSuccess('Success', 'Your email address has been verified, you can now login.');
          $this->action = 'success';
        } else {
          $this->action = 'verify';
          $this->status->addError('Error', 'There was an error with either the Email Address, password, or your Date of Birth.');
        }
      }
    }
    // POST data but no token which means a resend
    else if (
      $get->isEmpty()
      && !$post->isEmpty()
      && !$post->exists('token')
    ) {

      $email = $post->getEmail('email');
      $password = $post->get('password', '');
      $dob = $post->getDate('dob');

      // Check inputs
      if (empty($email) || empty($password) || !isset($dob)) {
        $this->action = 'verify';
        $this->status->addError('Error', 'There was an error with either the Email Address, password, or your Date of Birth.');
      } else {
        $user = new User($this->app->db);
        $user->loadFromEmail($email, $dob);

        if ($user->id > 0 && password_verify($password, $user->password)) {
          // Resend token
          $token = $user->addToken('Verify');
          $link = 'https://' . $app->conf->site->url . '/verify?token=' . $token;

          if ($this->app->email->send(
            $user,
            'Please Verify your Email Address',
            'To verify your email address please visit ' . $link,
            'Verify',
            [
              'firstname' => $user->firstname,
              'link' => $link
            ]
          )) {
            //$this->status->addSuccess('Succes', '');
          }
        } else {
          $this->status->addError('Error', 'There was an error with either the Email Address, password, or your Date of Birth.');
        }
      }
    }
    // No idea what happened but it's not expected.
    else {
      // Somthing isn't right, fail
      $this->action = 'error';
      $this->status->addError('Token Error', 'There is no security token provided.');
    }
  }
}
