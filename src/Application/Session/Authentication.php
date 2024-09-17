<?php

namespace Adept\Application\Session;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;
use \Adept\Application\Session\Data;
use \Adept\Application\Session\Request;
use \Adept\Data\Item\User;

class Authentication
{

  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Session\Data
   */
  protected Data $data;

  /**
   * Failed login delay in seconds
   *
   * @var int
   */
  public int $delay = 0;

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
  public function __construct(Data &$data)
  {
    $this->data = $data;
    $this->user = new User();

    if (($id = $data->server->getInt('auth.userid', 0)) > 0) {
      $this->user->loadFromId($id);
      $this->status = ($this->user->id > 0 && $this->user->verified != '0000-00-00 00:00:00');
    }
  }

  /**
   * Undocumented function
   *
   * @param  string                             $username
   * @param  string                             $password
   * @param  \Adept\Application\Session\Request $request
   *
   * @return bool
   */
  public function login(string $username, string $password, \Adept\Application\Session\Request &$request): bool
  {
    $result = 'Fail';
    $failed = '';

    if (!$this->status) {

      $db = \Adept\Application::getInstance()->db;
      $params = [$username];

      // Username isn't in a security hold (timeout)

      // TODO: Move to \Adept\Data\Item\User
      $query  = "SELECT * FROM `User`";
      $query .= " WHERE `username` = ?";
      $query .= " AND `password` <> ''";

      $user = $db->getObject($query, $params);

      if (isset($user->id)) {
        // Username exists

        if ($user->status) {
          // User status is active

          if ($user->verified != '0000-00-00 00:00:00') {
            // User has been verified

            if (password_verify($password, $user->password)) {
              // Password matches

              $result = 'Success';
              $session = $this->data->server->getInt('session.id', 0);

              $this->data->server->set('auth.userid', $user->id);
              $this->data->server->set('auth.token', '');

              $db->update(
                "UPDATE `Session` SET `token` = ? WHERE `id` = ?",
                [$session, $this->newToken()]
              );
            } else {
              // Password is incorrect

              $result = 'Fail';
              $failed = 'Password';
            }
          } else {
            // User hasn't verified their email address

            $result = 'Fail';
            $failed = 'Verified';
          }
        } else {
          // User is deactivated via the status value
          $result = 'Fail';
          $failed = 'Deactivated';
        }
      } else {
        // No username exists
        $result = 'Fail';
        $failed = 'Nonexistent';
      }
    } else {
      // Username is in a timeout for being bad
      $result = 'Delay';
    }

    $query = "INSERT INTO `LogAuth`";
    $query .= " (`username`, `useragent`, `ipaddress`, `result`, `failed`)";
    $query .= " VALUES";
    $query .= " (?,?,?,?,?)";

    $params = [
      $username,
      $request->useragent->id,
      $request->ip->id,
      $result,
      $failed
    ];

    $db->insert($query, $params);

    return ($result == 'Success');
  }

  public function logout()
  {
    $this->data->server->purge();
  }

  public static function newToken(
    int $length = 32,
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
    $db = \Adept\Application::getInstance()->db;

    return ($db->getInt(
      "SELECT COUNT(*) FROM `user_token` WHERE `type` = ? AND `token` = ? AND `expires` > NOW() ",
      [$type, $token]
    ) > 0);
  }

  //public function tokenCheck(string $type, string $token, string $username, string $password, \DateTime $dob): bool
  public function tokenCheck(string $type, string $token, string $username, string $password): bool
  {
    $db = \Adept\Application::getInstance()->db;
    $status = false;

    $query  = "SELECT a.id, a.password";
    $query .= " FROM `user` AS a";
    $query .= " INNER JOIN `user_token` AS b ON a.id = b.user";
    //$query .= " WHERE a.username = ? AND a.dob = ?";
    $query .= " WHERE a.username = ?";
    $query .= " AND b.type = ? AND b.token = ? AND b.expires > NOW()";

    // Check DB for user
    $user = $db->getObject(
      $query,
      //[$username, $dob->format('Y-m-d 00:00:00'), $type, $token]
      [$username, $type, $token]
    );

    if (is_object($user) && $user->id > 0) {
      if (password_verify($password, $user->password)) {
        // Login
        $this->data->server->set('auth.userid', $user->id);
        $this->user = new User($db, $user->id);
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
    $db = \Adept\Application::getInstance()->db;

    return ($db->getInt(
      "SELECT COUNT(*) FROM `user_token` WHERE `type` = ? AND `token` = ? AND `created` < NOW() AND `expires` > NOW()",
      [$type, $token]
    ) == 1);
  }
}
