<?php

namespace Adept\Document\HTML;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration;
use \Adept\Application\Session\Request;
use \Adept\Document\HTML\Head\CSS;
use \Adept\Document\HTML\Head\JavaScript;
use \Adept\Document\HTML\Head\Link;
use \Adept\Document\HTML\Head\Meta;


class Head
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Document\HTML\Head\CSS
   */
  public CSS $css;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $description = '';

  /**
   * Undocumented variable
   *
   * @var \Adept\Document\HTML\Head\JavaScript
   */
  public JavaScript $javascript;

  /**
   * Undocumented variable
   *
   * @var \Adept\Document\HTML\Head\Link
   */
  public Link $link;

  /**
   * Undocumented variable
   *
   * @var \Adept\Document\HTML\Head\Meta
   */
  public Meta $meta;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $title = '';

  public function __construct()
  {
    $this->meta = new Meta();
    $this->link = new Link();
    $this->css = new CSS();
    $this->javascript = new JavaScript();

    // TODO: Add checks to see if files exist before adding
    $this->link->add('/favicon.ico', 'icon', ['size' => 'any']);
    $this->link->add('/favicon.svg', 'icon', ['type' => 'image/svg+xml']);

    $this->meta->add('viewport', 'width=device-width, initial-scale=1, minimum-scale=1.0, user-scalable=yes');
  }

  public function getBuffer(): string
  {
    $app = \Adept\Application::getInstance();
    $conf = $app->conf->site;

    $html = '<base href="https://' . $app->session->request->url->host . '">';
    $html .= '<title>' . $this->meta->title . ' - ' . $conf->name . '</title>';

    $html .= $this->meta->getBuffer();
    $html .= $this->link->getBuffer();
    $html .= $this->css->getBuffer();
    $html .= $this->javascript->getBuffer();

    $html = str_replace('><', ">\n  <", $html);

    return $html;
  }
}
