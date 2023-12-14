<?php

namespace Adept\Application\Session;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;
use \Adept\Application\Session\Data;
use \Adept\Data\Item\User;

class Authentication
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Database
   */
  protected Database $db;

  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Session\Data
   */
  protected Data $data;

  /**
   * Undocumented variable
   *
   * @var bool
   */
  public bool $status = false;

  /**
   * The current user if logged in
   *
   * @var \Adept\Data\Item\User
   */
  public User $user;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database      $db
   * @param  \Adept\Application\Session\Data  $data
   */
  public function __construct(Database &$db, Data &$data)
  {
    $this->db = $db;
    $this->data = $data;
    $this->user = new User($db, (($id = $data->server->getInt('auth.userid', 0)) > 0) ? $id : 0);
    $this->status = ($this->user->id > 0 && $this->user->verified->format('Y') != '-0001');
  }

  public function login(string $username, string $password): bool
  {
    $status = $this->status;

    if (!$this->status) {
      $db = $this->db;

      $params = [$username];

      $query  = "SELECT `id`, `password` FROM `user`";
      $query .= " WHERE `username` = ?";
      $query .= " AND `password` <> ''";
      $query .= " AND `verified` <> '0000-00-00 00:00:00'";
      $query .= " AND `status` = 1";

      $user = $db->getObject($query, $params);

      if (isset($user->password) && password_verify($password, $user->password)) {
        $status = true;
        $this->data->server->set('auth.userid', $user->id);
      }
    }

    return $status;
  }

  public function logout()
  {
    $this->data->server->purge();
  }

  public static function newToken(
    $length = 32,
    bool $lower = true,
    bool $upper = true,
    bool $numbers = true
  ): string {

    $seed = '';

    if ($lower) {
      $seed .= 'abcdefghijklmnopqrstuvwxyz';
    }

    if ($upper) {
      $seed .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }

    if ($numbers) {
      $seed .= '0123456789';
    }

    if (empty($seed)) {
      \Adept\Error::halt(E_ERROR, 'Error generating a secure token.', __FILE__, __LINE__);
    }


    $count = strlen($seed);
    $token = '';
    // Generate initial random bytes
    $bytes = random_bytes($count);
    // Make sure we only pick characters that are in our pool of accepted characters
    for ($i = 0; $i < $length; $i++) {
      $token .= $seed[mt_rand(0, $count - 1)];
    }

    return $token;
  }

  public function tokenValid(string $type, string $token): bool
  {
    return ($this->db->getInt(
      "SELECT COUNT(*) FROM `user_token` WHERE `type` = ? AND `token` = ? AND `expires` > NOW() ",
      [$type, $token]
    ) > 0);
  }

  //public function tokenCheck(string $type, string $token, string $username, string $password, \DateTime $dob): bool
  public function tokenCheck(string $type, string $token, string $username, string $password): bool
  {
    $status = false;

    $query  = "SELECT a.id, a.password";
    $query .= " FROM `user` AS a";
    $query .= " INNER JOIN `user_token` AS b ON a.id = b.user";
    //$query .= " WHERE a.username = ? AND a.dob = ?";
    $query .= " WHERE a.username = ?";
    $query .= " AND b.type = ? AND b.token = ? AND b.expires > NOW()";

    // Check DB for user
    $user = $this->db->getObject(
      $query,
      //[$username, $dob->format('Y-m-d 00:00:00'), $type, $token]
      [$username, $type, $token]
    );

    if (is_object($user) && $user->id > 0) {
      if (password_verify($password, $user->password)) {
        // Login
        $this->data->server->set('auth.userid', $user->id);
        $this->user = new User($this->db, $user->id);
        $this->user->delToken($type);
        $this->status = true;

        // Update status
        $status = true;
      }
    }

    return $status;
  }

  public function tokenExists(string $type, string $token): bool
  {
    return ($this->db->getInt(
      "SELECT COUNT(*) FROM `user_token` WHERE `type` = ? AND `token` = ? AND `created` < NOW() AND `expires` > NOW()",
      [$type, $token]
    ) == 1);
  }
}
