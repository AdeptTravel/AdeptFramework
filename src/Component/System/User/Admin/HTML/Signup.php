<?php

namespace Adept\Component\User\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;
use \Adept\Data\Item\Location;
use \Adept\Data\Item\Phone;
use \Adept\Data\Item\User;
use \Adept\Document\HTML\Body\Status;

class Signup extends \Adept\Abstract\Component
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

    $this->status = new Status();

    $post    = &$app->session->request->data->post;
    $user    = &$app->session->auth->user;
    $address = new Location($app->db);
    $phone   = new Phone($app->db);

    if (!$post->isEmpty()) {

      $address->loadFromPost($post);
      $address->save();

      if (empty($address->error)) {

        $phone->loadFromPost($post);
        $phone->save();

        if (empty($phone->error)) {

          $user->loadFromPost($post);


          if ($user->save()) {

            $token = $user->addToken('Verify');
            $link = 'https://' . $app->conf->site->url . '/verify?token=' . $token;

            $user->map('user_phone', [
              'user' => $user->id,
              'phone' => $phone->id
            ]);

            $user->map('user_address', [
              'user' => $user->id,
              'address' => $address->id
            ]);

            if ($app->email->send(
              $user,
              'Please Verify your Email Address',
              'To verify your email address please visit ' . $link,
              'Verify',
              [
                'firstname' => $user->firstname,
                'link' => $link
              ]
            )) {
              $this->status->addSuccess('Succes', 'Check your email for the next steps.');
            }
          } else {
            array_push($this->status->error, ...$user->error);
          }
        } else {
          array_push($this->status->error, ...$phone->error);
        }
      } else {
        array_push($this->status->error, ...$address->error);
      }
    }
  }
}
