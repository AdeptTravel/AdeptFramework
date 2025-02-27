<?php

namespace Adept\Application;

use \PDO;

// Prevent direct access to the script
defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration;
use \Adept\Data\Item\User;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
use \PHPMailer\PHPMailer\SMTP;

/**
 * \Adept\Application\Email
 *
 * Handles sending emails using PHPMailer
 *
 * @package    AdeptFramework
 * @author     Brandon J.
 * Yaniz (brandon@yaniz.io)
 * @copyright  2021-2024
 * The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 * @version    1.0.0
 */
class Email
{
  /**
   * PHPMailer instance used for sending emails
   *
   * @var \PHPMailer\PHPMailer\PHPMailer
   */
  protected $mail;

  /**
   * Constructor
   *
   * Initializes the PHPMailer object with SMTP configuration
   *
   * @param \Adept\Abstract\Configuration $conf Configuration object containing email settings
   */
  public function __construct(Configuration $conf)
  {
    // Create a new PHPMailer instance with exceptions enabled
    $this->mail = new PHPMailer(true);
    // Disable SMTP debug output
    $this->mail->SMTPDebug  = SMTP::DEBUG_OFF;
    // Set mailer to use SMTP
    $this->mail->isSMTP();
    // Specify main SMTP server
    $this->mail->Host       = $conf->email->host;
    // Enable SMTP authentication
    $this->mail->SMTPAuth   = true;
    // SMTP username
    $this->mail->Username   = $conf->email->username;
    // SMTP password
    $this->mail->Password   = $conf->email->password;
    // Enable TLS encryption; 'ssl' is also accepted
    $this->mail->SMTPSecure = 'tls';
    // TCP port to connect to
    $this->mail->Port       = 587;
    // Set the sender's email and name
    $this->mail->setFrom(
      $conf->email->from->email,
      $conf->email->from->name
    );
    // Set email format to HTML if specified in configuration
    $this->mail->isHTML($conf->email->html);
  }

  /**
   * Send an email to a user
   *
   * @param User   $user     User object containing recipient information
   * @param string $subject  Email subject
   * @param string $text     Plain text version of the email body
   * @param string $template Template name for the HTML email
   * @param array  $params   Parameters to replace in the template
   *
   * @return bool            Returns true if the email was sent successfully
   */
  public function send(
    User $user,
    string $subject,
    string $text,
    string $template,
    array $params
  ): bool {

    // Add recipient's email address and name
    $this->mail->addAddress($user->username, $user->firstname . ' ' . $user->lastname);

    // Get the HTML email body from the template
    $html = $this->getTemplate($template, $params);

    // Set email subject
    $this->mail->Subject = $subject;
    // Set email body in HTML format
    $this->mail->Body    = $html;
    // Set alternative plain text body
    $this->mail->AltBody = $text;

    // Send the email and capture the status
    $status = $this->mail->send();
    // TODO: Add email logging here

    return $status;
  }

  /**
   * Get the email template content with parameters replaced
   *
   * @param string $action Template name
   * @param array  $params Parameters to replace in the template
   *
   * @return string        The processed HTML content of the email
   */
  protected function getTemplate(string $action, array $params = []): string
  {
    // Construct the file path to the template
    $file = FS_TEMPLATE . '/Email/' . $action . '.html';
    $html = '';

    // Check if the template file exists
    if (file_exists($file)) {
      // Get the content of the template file
      $html = file_get_contents($file);

      // Replace placeholders with actual parameters
      foreach ($params as $k => $v) {
        $html = str_replace('{' . $k . '}', $v, $html);
      }
    }

    return $html;
  }
}
