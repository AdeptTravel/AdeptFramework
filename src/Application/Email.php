<?php

namespace Adept\Application;

use \PDO;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration;
use \Adept\Data\Item\User;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
use \PHPMailer\PHPMailer\SMTP;

class Email
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration
   */
  protected Configuration $conf;

  /**
   * Undocumented variable
   *
   * @var \PHPMailer\PHPMailer\PHPMailer
   */
  protected $mail;

  /**
   * Undocumented function
   *
   * @param  \Adept\Abstract\Configuration $conf
   */
  public function __construct(Configuration $conf)
  {
    $this->conf = $conf;

    $this->mail = new PHPMailer(true);
    $this->mail->SMTPDebug  = SMTP::DEBUG_OFF;                         // Enable verbose debug output 2
    $this->mail->isSMTP();                              // Set mailer to use SMTP
    $this->mail->Host       = $conf->email->host;       // Specify main SMTP server
    $this->mail->SMTPAuth   = true;                     // Enable SMTP authentication
    $this->mail->Username   = $conf->email->username;   // SMTP usernamethis->
    $this->mail->Password   = $conf->email->password;   // SMTP password
    $this->mail->SMTPSecure = 'tls';                    // Enable TLS encryption, 'ssl' also accepted
    $this->mail->Port       = 587;                      // TCP port to connect to
    $this->mail->setFrom(
      $conf->email->from->email,
      $conf->email->from->name
    );
    $this->mail->isHTML($conf->email->html);
  }

  public function send(
    User $user,
    string $subject,
    string $text,
    string $template,
    array $params
  ): bool {

    $this->mail->addAddress($user->username, $user->firstname . ' ' . $user->lastname);

    $html = $this->getTemplate($template, $params);

    $this->mail->Subject = $subject;
    $this->mail->Body    = $html;
    $this->mail->AltBody = $text;

    $status = $this->mail->send();

    //die('<pre>' . print_r($this->mail, true));
    // TODO: Add email logging here

    return $status;
  }

  protected function getTemplate(string $action, array $params = []): string
  {
    $file = FS_TEMPLATE . '/Email/' . $action . '.html';
    $html = '';

    if (file_exists($file)) {

      $html = file_get_contents($file);

      foreach ($params as $k => $v) {
        $html = str_replace('{' . $k . '}', $v, $html);
      }
    }

    return $html;
  }
}
