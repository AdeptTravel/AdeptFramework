<?php

/**
 * \Adept\Document\HTML
 *
 * The document object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Document;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Document\HTML\Head;

/**
 * \Adept\Document\HTML
 *
 * The document object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class HTML extends \Adept\Abstract\Document
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Document\HTML\Head
   */
  public Head $head;

  public function __construct()
  {
    $app = \Adept\Application::getInstance();

    // Shortcuts
    $request  = &$app->session->request;

    // Set content type header
    header('Content-type: ' . $request->url->mime);

    $this->head = new Head();
  }

  public function getBuffer(): string
  {
    $app = \Adept\Application::getInstance();
    // Shortcuts
    $conf      = &$app->conf;
    $session   = &$app->session;
    $request   = &$session->request;
    $route     = &$request->route;
    $component = &$this->component;

    // HTML specific stuff
    $template = (!empty($route->template)) ? $route->template : $conf->site->template;

    $file = '';

    if (($file = $this->getFile([FS_SITE_TEMPLATE, FS_CORE_TEMPLATE], $template .  '/Template.php')) === false) {
      \Adept\Error::halt(
        E_ERROR,
        'Template Error<br>Template: ' . $template,
        __FILE__,
        __LINE__
      );
    }

    ob_start();
    include($file);
    $buffer = ob_get_contents();
    ob_end_clean();

    // Component
    $buffer = $this->replace('{{component}}', $component->getBuffer(), $buffer);

    // Title
    $buffer = $this->replace('{{title}}', $this->head->meta->title, $buffer);
    //die('<pre>' . print_r($this->head->meta, true));
    $pos  = 0;

    // Factory class for modules
    $modules = new \Adept\Document\HTML\Module();

    while (($start = strpos($buffer, '{{', $pos)) !== false) {
      $end = strpos($buffer, '}}', $start) + 2;
      $len = $end - $start;

      if ($end === false) {
        // No closing }} found, break the loop
        break;
      }

      $tag = substr($buffer, $start + 2, $len - 4);

      if (substr($tag, 0, 6) != 'module') {
        $pos = $end;
        continue;
      }

      $buffer = substr($buffer, 0, $start) . $modules->getModule($tag) . substr($buffer, $end);

      $pos = $end;
    }

    // Head
    $buffer = str_replace('{{head}}', $this->head->getBuffer(), $buffer);

    return $buffer;
  }

  public function replace(string $search, string $replace, string $subject): string
  {
    $pos = strpos($subject, $search);

    return ($pos !== false)
      ? substr_replace($subject, $replace, $pos, strlen($search))
      : $subject;
  }
}
