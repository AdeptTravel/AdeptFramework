<?php

namespace Adept\Data\Item;

use DateTime;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Session\Authentication;
use \Adept\Application\Session\Request\Data\Post;
use \Adept\Application\Database;
use \Adept\Data\Item\Location;
use \Adept\Data\Item\Phone;

class User extends \Adept\Abstract\Data\Item
{

  protected string $name = 'User';
  protected string $table = 'user';

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $advisor = 0;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $username = '';

  /**
   * Encrypted password
   *
   * @var string
   */
  public string $password = '';

  //public string $prefix = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $firstname;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $middlename;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $lastname;

  /**
   * Undocumented variable
   *
   * @var DateTime
   */
  public DateTime $dob;

  /**
   * public DateTime $dob;
   *
   * @var DateTime
   */
  public DateTime $created;

  /**
   * public DateTime $dob;
   *
   * @var DateTime
   */
  public DateTime $verified;

  /**
   * public DateTime $dob;
   *
   * @var DateTime
   */
  public DateTime $validated;

  /**
   * Undocumented variable
   *
   * @var bool
   */
  public bool $status = false;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  int                         $id
   */
  public function __construct(Database $db, int $id = 0)
  {
    parent::__construct($db, $id);

    $this->connections = [
      'address',
      'phone'
    ];

    $this->duplicateKeys = [
      'username'
    ];

    $this->excludeKeys = [
      'success'
    ];

    $this->excludeKeysOnNew = [
      'created',
      'verified',
      'validated',
      'success'
    ];

    $this->postFilters = [
      'username' => 'email',
    ];
  }

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
    parent::loadFromPost($post, $prefix);

    if (!empty($prefix)) {
      $prefix = $prefix . '_';
    }

    $this->username = $post->getEmail($prefix . 'email');
    $this->password = $this->setPassword(
      $post->getRaw($prefix . 'password0', ''),
      $post->getRaw($prefix . 'password1', '')
    );

    $this->firstname = $post->getName($prefix . 'firstname');
    $this->middlename = $post->getName($prefix . 'middlename');
    $this->lastname = $post->getName($prefix . 'lastname');

    $dob = $post->getDate('dob');

    if (isset($dob)) {
      $this->dob = $dob;
    } else {
      $this->setError('Date of Birth', 'There was a problem with the date of birth format.');
    }
  }

  public function loadFromEmail(string $username, \DateTime $dob): bool
  {
    //$status = $this->loadCache();
    $status = false;

    $query  = 'SELECT * FROM `' . $this->table . '` AS a';
    $query .= ' WHERE `username` = ? AND `dob` = ?';

    if (($obj = $this->db->getObject(
      $query,
      [$username, $dob->format('Y-m-d 00:00:00')]
    )) !== false) {
      $status = true;
      $this->loadFromObj($obj);
    }

    return $status;
  }

  public function save(string $table = ''): bool
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
    $this->delToken($type);

    $token = Authentication::newToken(32,);

    $this->db->insert(
      'INSERT INTO `user_token` (`user`, `type`, `token`) VALUES (?, ?, ?)',
      [$this->id, $type, $token]
    );

    return $token;
  }

  public function delToken(string $type)
  {
    $this->db->update(
      'DELETE FROM user_token WHERE user = ? AND type = ?',
      [$this->id, $type]
    );
  }

  protected function isDuplicate(string $table = ''): bool
  {
    $count = $this->db->getInt(
      'SELECT COUNT(*) FROM `user` WHERE `username` = ?',
      [$this->username]
    );

    return ($count > 0);
  }
}
