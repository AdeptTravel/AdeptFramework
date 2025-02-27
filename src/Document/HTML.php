<?php

namespace Adept\Document;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Document\HTML\Head;
use Adept\Document\HTML\Map;
use Adept\Document\HTML\Menu;
use Adept\Document\HTML\Module;

class HTML extends \Adept\Abstract\Document
{
  public Head $head;
  public Menu $menu;
  public Map  $map;

  public function __construct()
  {
    $app = Application::getInstance();

    // Shortcuts
    $request  = &$app->session->request;

    // Set content type header
    header('Content-type: ' . $request->url->mime);

    $this->head = new Head();
    $this->menu = new Menu();
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
    $modules   = new Module();

    $buffer = $app->debug->getBuffer();

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
    $buffer .= ob_get_contents();
    ob_end_clean();


    // Component
    $buffer = $this->replace('{{component}}', $component->getBuffer(), $buffer);

    // Title
    $buffer = $this->replace('{{title}}', $this->head->meta->title, $buffer);

    $pos = 0;

    while (($start = strpos($buffer, '{{', $pos)) !== false) {

      $end = strpos($buffer, '}}', $start) + 2;
      $len = $end - $start;

      if ($end === false) {
        // No closing }} found, break the loop
        break;
      }

      $html  = '';
      $tag   = substr($buffer, $start + 2, $len - 4);
      $parts = explode(':', $tag);

      if (count($parts) >= 2) {
        if ($parts[0] == 'area' || $parts[0] == 'module') {
          $html = $modules->getBuffer($parts[0], $parts[1]);
        }
      }

      if ($parts[0] != 'head') {
        $buffer = substr($buffer, 0, $start) . $html . substr($buffer, $end);
      }

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
