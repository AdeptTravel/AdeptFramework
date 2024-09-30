<?php

/**
 * \Adept\Abstract\Document
 *
 * The document object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Abstract\Document
 *
 * The document object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
abstract class Document
{
  /**
   * Undocumented variable
   *
   * @var string
   */
  protected string $buffer;

  /**
   * The component object
   *
   * @var \Component
   */
  public $component;

  public function __construct()
  {
    $app = \Adept\Application::getInstance();

    // Shortcuts
    $request  = &$app->session->request;

    // Set content type header
    header('Content-type: ' . $request->url->mime);
  }

  protected function getComponentNamespace(): string
  {
    $app = \Adept\Application::getInstance();

    // Shortcuts
    $session  = &$app->session;
    $request  = &$session->request;
    $route    = &$request->route;
    $url      = &$request->url;

    // Used to determine the component, option, and format
    $component  = $route->component;
    $option     = $route->view;
    $format     = $url->type;
    $namespace  = null;

    if (!class_exists($namespace = "\\Adept\\Component\\$component\\$format\\$option")) {
      // TODO: Remove die and allow for 404 to do it's thing
      die('Not found: ' . $namespace);
      $namespace = "\\Adept\\Component\\Error\\$format\\Error";
      $request->setStatus(404);
    }

    return $namespace;
  }

  public function loadComponent()
  {
    $component = $this->getComponentNamespace();
    $this->component = new $component();
  }

  protected function getFile(array $paths, string $path): string|bool
  {
    $file = false;

    for ($i = 0; $i < count($paths); $i++) {
      if (file_exists($file = $paths[$i] . $path)) {
        break;
      }
    }

    return $file;
  }

  abstract public function getBuffer(): string;

  public function render()
  {
    echo $this->getBuffer();
  }
}
