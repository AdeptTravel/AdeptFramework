<?php

namespace Adept\Document;

defined('_ADEPT_INIT') or die();

use \Adept\Application;
use \Adept\Document\HTML\Controls;
use \Adept\Document\HTML\Head;
use \Adept\Document\HTML\Body\Menu;
//use \Adept\Error;

class HTML extends \Adept\Abstract\Document
{
  /**
   * Template HTML
   *
   * @var string
   */
  protected string $html;


  public \Adept\Document\HTML\Controls $controls;

  /**
   * The head object (HTML only)
   * 
   * @var \Adept\Document\HTML\Head
   */
  public Head $head;

  /**
   * The menu object (HTML only)
   *
   * @var \Adept\Document\HTML\Body\Menu
   */
  public Menu $menu;

  /**
   * Init
   */
  public function __construct(Application &$app)
  {
    $conf = $app->conf;

    $session = $app->session;
    $request = $session->request;
    //$auth = $session->auth;

    $this->html = '';

    $template = $request->route->template;

    //if (empty($template)) {
    //  $template = $conf->site->template;
    //}

    $path = FS_TEMPLATE . $template;
    $file = $path . '/Template.php';

    if (!file_exists($file)) {
      $template = str_replace(FS_TEMPLATE, '', $path);
      $template = substr($template, 0, strlen($template) - 1);

      \Adept\Error::halt(
        E_ERROR,
        'Template Error ' . $path . ' - ' . $template,
        __FILE__,
        __LINE__
      );
    }

    $this->head = new Head($conf, $request);
    $this->controls = new Controls($this->head);
    $this->menu = new Menu($app);

    ob_start();
    include($file);
    $this->html = ob_get_contents();
    ob_end_clean();

    parent::__construct($app);
  }

  public function getTitle(): string
  {
    return $this->head->title;
  }

  public function setTitle(string $title)
  {
    $this->head->title = $title;
  }

  public function getDescription(): string
  {
    return $this->head->description;
  }

  public function setDescription(string $description)
  {
    $this->head->meta->description = $description;
  }

  public function getBuffer(): string
  {
    //$head = $this->head;

    $html = $this->html;

    // Title
    $html = str_replace('{{title}}', $this->head->meta->title, $html);

    // Component
    $html = str_replace('{{component}}', $this->component->getBuffer(), $html);

    // Menus

    preg_match_all('/\{\{menu.+?\}\}/', $html, $matches);

    foreach ($matches[0] as $match) {
      $obj = $this->parseTag($match);
      $html = str_replace($match, $this->menu->getBuffer($obj->name, $obj->args), $html);
    }

    // Modules
    $modules = new \Adept\Document\HTML\Body\Modules($this->app, $this);

    preg_match_all('/\{\{module.+?\}\}/', $html, $matches);

    foreach ($matches[0] as $match) {
      $obj = $this->parseTag($match);
      $module = $modules->getModule($obj->name, $obj->args);
      $html = str_replace($match, ($module !== false) ? $module->getBuffer() : '', $html);
    }

    /*
    preg_match_all('/\{\{form.+?\}\}/', $html, $matches);

    foreach ($matches[0] as $match) {
      $tag = substr($match, 2, strlen($match) - 4);
      $parts = explode(':', $tag);
      $alias = $parts[1];
      $args = [];

      if (count($parts) > 2) {
        unset($parts[0]);
        unset($parts[1]);
        $args = array_values($parts);
      }

      //$form = $this->getForm($alias);

      //$html = str_replace($match, $form->getBuffer(), $html);
    }
    */

    // Head
    $html = str_replace('{{head}}', $this->head->getBuffer(), $html);

    // Controls
    $html = str_replace('{{controls}}', $this->controls->getBuffer(), $html);

    return $html;
  }

  public function save()
  {
    //$html5->save($dom, 'out.html');
  }

  protected function parseTag(string $tag): object
  {
    $obj = new \stdClass();

    // Remove {{}}
    $tag = substr($tag, 2, strlen($tag) - 4);

    // Break apart
    $parts = explode(':', $tag);

    $obj->type = $parts[0];
    $obj->name = $parts[1];
    $obj->args = [];

    if (count($parts) > 2) {
      unset($parts[0]);
      unset($parts[1]);
      $obj->args = (object)array_values($parts);
    }

    return $obj;
  }
}
