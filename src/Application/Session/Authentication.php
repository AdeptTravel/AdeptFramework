<?php

namespace AdeptCMS\Application\Session;

defined('_ADEPT_INIT') or die('No Access');

class Authentication
{
  /**
   * A reference to the database object
   * 
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * The login token.  This token shifts with each request and is sent to user
   * this will allow for long sessions (ie. mobile apps) without having to
   * constantly login.
   *
   * @var string
   */
  protected $token;

  /**
   * User ID
   *
   * @var int
   */
  public $id;

  /**
   * Username
   *
   * @var string
   */
  public $username;

  /**
   * The authentication status
   *
   * @var bool
   */
  public $status;

  /**
   * Is an administrator
   *
   * @var bool
   */
  public $admin;

  public function __construct(\AdeptCMS\Application\Database &$db)
  {
    $this->db = $db;
    $this->token = '';
    $this->id = 0;
    $this->username = '';
    $this->status = false;
    $this->admin = false;

    if (
      isset($_SESSION['auth.userid'])
      && isset($_SESSION['auth.username'])
      && isset($_SESSION['auth.token']) && strlen($_SESSION['auth.token']) == 32
    ) {

      $params = [
        $_SESSION['auth.userid'],
        $_SESSION['auth.username'],
        $_SESSION['auth.token']
      ];

      $query  = "SELECT `admin` FROM `user`";
      $query .= " WHERE `id` = ?";
      $query .= " AND `username` = ?";
      $query .= " AND `token` = ?";
      $query .= " AND `verified` <> '0000-00-00 00:00:00'";
      $query .= " AND `status` = 1";

      $user = $db->getObject($query, $params);

      if (is_object($user) && isset($user->admin)) {
        $this->id = $_SESSION['auth.userid'];
        $this->token = $_SESSION['auth.token'];
        $this->username = $_SESSION['auth.username'];
        $this->admin = $user->admin;
        $this->status = true;

        $this->updateToken();
      } else {
        $this->logout();
      }
    } else {
      $this->logout();
    }
  }

  public function login(string $username, string $password): bool
  {
    $status = false;

    if ($this->status) {
      $status = true;
    } else {

      $params = [$username];

      $query  = "SELECT `id`, `password`, `admin` FROM `user`";
      $query .= " WHERE `username` = ?";
      $query .= " AND `verified` <> '0000-00-00 00:00:00'";
      $query .= " AND `status` = 1";

      $user = $this->db->getObject($query, $params);

      if (isset($user->password) && password_verify($password, $user->password)) {
        $status = true;
        $_SESSION['auth.admin'] = $user->admin;
        $_SESSION['auth.userid'] = $user->id;
        $_SESSION['auth.username'] = $username;

        $this->id = $user->id;
        $this->username = $username;
        $this->admin = $user->admin;
        $this->status = true;

        $this->updateToken();

        $redirect = '/' . $_SESSION['redirect'];

        if (isset($redirect)) {

          unset($_SESSION['redirect']);

          $url = new \AdeptCMS\Data\Item\Url($this->db);

          if ($redirect != $url->path) {
            header('Location: ' . $redirect, true);
            die();
          }
        }
      }
    }

    return $status;
  }

  public function logout()
  {
    $this->db->update(
      "UPDATE user SET token = '' WHERE id = ?",
      [$this->id]
    );

    unset($_SESSION['auth.admin']);
    unset($_SESSION['auth.token']);
    unset($_SESSION['auth.userid']);
    unset($_SESSION['auth.username']);
  }

  protected function updateToken()
  {
    $seed = $this->id . ':' . $this->username . ':' . microtime(true);
    $hash = hash('md5', $seed);

    if ($this->db->update(
      'UPDATE user SET token = ? WHERE id = ?',
      [$hash, $this->id]
    ) !== false) {
      $this->token = $hash;
      $_SESSION['auth.token'] = $hash;
    }
  }
}
