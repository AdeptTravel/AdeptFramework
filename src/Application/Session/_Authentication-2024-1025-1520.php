<?php

namespace Adept\Application\Session;

// Prevent direct access to the script
defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;
use Adept\Application\Session\Data;
use Adept\Data\Item\User;

/**
 * \Adept\Application\Session\Authentication
 *
 * Handles user authentication within the session, including login delay mechanisms
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 * @version    1.0.0
 */
class Authentication
{
  /**
   * Session data object
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
   * Authentication status
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
   * Constructor
   *
   * Initializes the Authentication object with session data.
   *
   * @param \Adept\Application\Session\Data $data Reference to the session data object
   */
  public function __construct(Data &$data)
  {
    $this->data = $data;
    $this->user = new User();

    // Check if a user ID is stored in session data
    if (($id = $data->server->getInt('auth.userid', 0)) > 0) {
      $this->user->loadFromId($id);
      $this->status = (
        $this->user->id > 0 &&
        $this->user->status == 'Active' &&
        $this->user->verifiedOn != '0000-00-00 00:00:00'
      );
    }
  }

  /**
   * Attempts to log in a user with provided credentials
   * Implements a delay mechanism for failed login attempts
   *
   * @param string $username The username provided by the user
   * @param string $password The password provided by the user
   *
   * @return bool Returns true if login is successful, false otherwise
   */
  public function login(string $username, string $password): bool
  {
    $result = 'Fail';
    $reason = '';
    $this->delay = 0; // Reset delay

    $session = Application::getInstance()->session;
    $request = &$session->request;
    $db = Application::getInstance()->db;

    // Fetch failed login attempts within the last 60 minutes
    $failedAttempts = $this->getFailedAttempts($username);

    if (count($failedAttempts) > 0) {
      // Calculate delay based on the number of failed attempts
      $this->calculateDelay($failedAttempts);
      $lastAttemptTime = strtotime($failedAttempts[0]->createdAt);
      $elapsedTime = time() - $lastAttemptTime;

      if ($elapsedTime < $this->delay) {
        // Required delay period has not passed
        //$result = 'Delay';
        //$reason = 'Please wait ' . ($this->delay - $elapsedTime) . ' seconds before retrying.';
        //$this->logAuthAttempt($session, $request, $username, $result);
        $this->logAuthAttempt($session, $request, $username, 'Delay');
        return false;
      }
    }

    // Proceed with login attempt
    $params = [$username];

    // TODO: Move to \Adept\Data\Item\User
    $query  = "SELECT * FROM `User`";
    $query .= " WHERE `username` = ?";
    $query .= " AND `password` <> ''";

    $users = $db->getObjects($query, $params);
    $user  = null;

    $result = 'Fail';
    $reason = 'Nonexistent';

    foreach ($users as $user) {
      if (!empty($user->id)) {
        // Username exists

        if ($user->status == 'Active') {
          // User status is active

          if ($user->verifiedOn != '0000-00-00 00:00:00') {
            // User has been verified
            if (password_verify($password, $user->password)) {
              // Password matches
              $this->status = true;

              $this->data->server->set('auth.userid', $user->id);
              $this->data->server->set('auth.token', $this->newToken());

              $session->token = $this->data->server->getString('auth.token');



              // Reset failed attempts after successful login
              $this->resetFailedAttempts($username);

              // Log the successful attempt
              $this->logAuthAttempt($session, $request, $username, 'Success');
              return true;
            } else {
              // Password is incorrect
              $result = 'Fail';
              $reason = 'Password';
            }
          } else {
            // User hasn't verified their email address
            $result = 'Fail';
            $reason = 'Unverified';

            // Break out of the loop
            break;
          }
        } else {
          // User is deactivated via the status value
          $result = 'Fail';
          //$reason = $user->status;
          $reson = 'Deactivated';
        }
      }
    }

    // Log the failed attempt
    $this->logAuthAttempt($session, $request, $username, $result, $reason);

    return false;
  }

  /**
   * Logs out the current user by purging session data
   *
   * @return void
   */
  public function logout()
  {
    $this->data->server->purge();
    $this->status = false;
  }

  /**
   * Generates a new secure token
   *
   * @param int  $length  Length of the token
   * @param bool $lower   Include lowercase letters
   * @param bool $upper   Include uppercase letters
   * @param bool $numbers Include numbers
   *
   * @return string The generated token
   */
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

    // Generate the token using the seed characters
    for ($i = 0; $i < $length; $i++) {
      $token .= $seed[random_int(0, $count - 1)];
    }

    return $token;
  }

  /**
   * Retrieves failed login attempts for a username within the last 60 minutes
   *
   * @param string $username The username to check
   *
   * @return array An array of failed login attempts
   */
  protected function getFailedAttempts(string $username): array
  {
    $db = Application::getInstance()->db;

    $query = "SELECT * FROM `LogAuth`";
    $query .= " WHERE `username` = ?";
    $query .= " AND `result` = 'Fail'";
    $query .= " AND `createdAt` >= DATE_SUB(NOW(), INTERVAL 60 MINUTE)";
    $query .= " ORDER BY `createdAt` DESC";

    return $db->getObjects($query, [$username]) ?: [];
  }

  /**
   * Calculates the delay based on the number of failed attempts
   *
   * @param array $failedAttempts An array of failed login attempts
   *
   * @return void
   */
  protected function calculateDelay(array $failedAttempts): void
  {
    // Start with a 3-second delay
    $this->delay = 3;

    // Double the delay for each subsequent failure
    $attempts = count($failedAttempts) - 1; // Exclude the first attempt
    for ($i = 0; $i < $attempts; $i++) {
      $this->delay *= 2;
    }

    // Cap the delay at 60 minutes (3600 seconds)
    if ($this->delay > 3600) {
      $this->delay = 3600;
    }
  }

  /**
   * Resets the failed login attempts for a username
   *
   * @param string $username The username to reset attempts for
   *
   * @return void
   */
  protected function resetFailedAttempts(string $username): void
  {
    $db = Application::getInstance()->db;

    // Optionally, delete failed attempts older than 60 minutes
    $query = "DELETE FROM `LogAuth`";
    $query .= " WHERE `username` = ?";
    $query .= " AND `result` = 'Fail'";
    $query .= " AND `createdAt` >= DATE_SUB(NOW(), INTERVAL 60 MINUTE)";

    //$db->execute($query, [$username]);
  }

  /**
   * Logs an authentication attempt
   *
   * @param \Adept\Application\Session       $session The current session object
   * @param \Adept\Application\Session\Request $request The current request object
   * @param string                           $username The username attempted
   * @param string                           $result   The result of the attempt ('Success', 'Fail', 'Delay')
   * @param string                           $reason   The reason for the result
   *
   * @return void
   */
  protected function logAuthAttempt($session, $request, string $username, string $result, string $reason = ''): void
  {
    //`result`      ENUM('Success', 'Fail', 'Delay'),
    //`reason`      ENUM('', 'Deactivated', 'Nonexistent', 'Password', 'Verified', 'Validated'),

    $db = Application::getInstance()->db;

    $query = "INSERT INTO `LogAuth`";
    $query .= " (`sessionId`, `requestId`, `useragentId`, `ipAddressId`, `username`, `result`, `reason`, `createdAt`)";
    $query .= " VALUES";
    $query .= " (?,?,?,?,?,?,?,NOW())";

    // Save the request to the DB to get the ID
    $request->save();

    $params = [
      $session->id,
      $request->request->id,
      $request->useragent->id,
      $request->ipAddress->id,
      $username,
      $result,
      $reason
    ];

    $db->insert($query, $params);
  }

  // ... (Other methods like tokenValid, tokenCheck, tokenExists remain unchanged)
}
