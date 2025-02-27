<?php

namespace Adept\Data\Item;

use DateTime;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Session\Authentication;
use \Adept\Application\Session\Request\Data\Post;
//use \Adept\Data\Item\Location;
//use \Adept\Data\Item\Phone;

class User extends \Adept\Abstract\Data\Item
{
  protected string $table = 'User';
  protected string $index = 'username';

  protected array  $excludeKeysOnNew = [
    'createdAt',
    'updatedAt',
    'verifiedOn',
    'validatedOn',
    'validatedBy'
  ];

  protected array  $postFilters = [
    'username' => 'email',
  ];

  public string    $username;
  public string    $password;
  public string    $firstName;
  public string    $middleName;
  public string    $lastName;
  public \DateTime $dob;
  public \DateTime $verifiedOn;
  public \DateTime $validatedOn;
  public int       $validatedBy;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Session\Request\Data\Post $post
   * @param  string                                       $prefix
   *
   * @return void
   */
  public function loadFromPost(Post $post, string $prefix = '')
  {
    // We are going to do additional work on the input so we will overwrite the
    // current loadFromPost.
    // parent::loadFromPost($post, $prefix);

    if (!empty($prefix)) {
      $prefix = $prefix . '_';
    }

    $this->username = $post->getEmail($prefix . 'email');

    if (!$post->isEmpty('password0') && !$post->isEmpty('password1')) {

      $password = $this->setPassword(
        $post->get($prefix . 'password0', ''),
        $post->get($prefix . 'password1', '')
      );

      if (!empty($password)) {
        $this->password = $password;
      }
    }

    $this->firstName  = $post->getName($prefix . 'firstName');
    $this->middleName = $post->getName($prefix . 'middleName');
    $this->lastName   = $post->getName($prefix . 'lastName');

    $dob = $post->getDate('dob');

    if (isset($dob)) {
      $this->dob = $dob;
    } else {
      $this->setError('Date of Birth', 'There was a problem with the date of birth format.');
    }
  }

  public function loadFromEmail(string $username, \DateTime $dob): bool
  {
    $app = \Adept\Application::getInstance();
    $status = false;

    $query  = 'SELECT * FROM `' . $this->table . '` AS a';
    $query .= ' WHERE `username` = ? AND `dob` = ?';

    if (($obj = $app->db->getObject(
      $query,
      [$username, $dob->format('Y-m-d 00:00:00')]
    )) !== false) {
      $status = true;
      $this->loadFromObj($obj);
    }

    return $status;
  }

  public function save(): bool
  {
    $isNew = ($this->id == 0);
    $status = parent::save();

    if ($isNew) {
      $this->addToken('Verify');
    }

    return $status;
  }

  public function setPassword(string $password0, string $password1): string
  {
    $password = '';

    if (!empty($password0)) {
      if ($password0 == $password1) {
        if (preg_match('/[A-Z]/', $password0)) {
          if (preg_match('/[a-z]/', $password0)) {
            if (preg_match('/[0-9]/', $password0)) {
              //if (preg_match('[@_!#$%^&*()<>?/|}{~:]', $password0)) {
              $password = password_hash($password0, PASSWORD_BCRYPT);
              //} else {
              //$this->setError('Password', 'Password must contain at least one special character');
              //}
            } else {
              $this->setError('Password', 'Password must contain at least one number');
            }
          } else {
            $this->setError('Password', 'Password must contain at least one lower case letter');
          }
        } else {
          $this->setError('Password', 'Password must contain at least one uppercase letter');
        }
      } else {
        $this->setError('Password', 'Passwords have to match');
      }
    }

    return $password;
  }

  public function addToken(string $type): string
  {
    $app = \Adept\Application::getInstance();

    $this->delToken($type);

    $token = Authentication::newToken(32,);

    $app->db->insert(
      'INSERT INTO `user_token` (`user`, `type`, `token`) VALUES (?, ?, ?)',
      [$this->id, $type, $token]
    );

    return $token;
  }

  public function delToken(string $type)
  {
    $app = \Adept\Application::getInstance();

    $app->db->update(
      'DELETE FROM user_token WHERE user = ? AND type = ?',
      [$this->id, $type]
    );
  }

  protected function duplicate(string $table = ''): bool
  {
    $app = \Adept\Application::getInstance();

    $count = $app->db->getInt(
      'SELECT COUNT(*) FROM `user` WHERE `username` = ?',
      [$this->username]
    );

    return ($count > 0);
  }
}
