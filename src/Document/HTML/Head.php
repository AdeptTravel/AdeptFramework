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
   * @var \Adept\Abstract\Configuration
   */
  protected Configuration $conf;

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
  public string $description;

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
  public string $title;

  public function __construct(&$conf, &$request)
  {
    $this->conf = $conf;
    $this->title = '';
    $this->description = '';
    $this->meta = new Meta($conf, $request);
    $this->link = new Link($conf, $request);
    $this->css = new CSS($conf, $request);
    $this->javascript = new JavaScript($conf, $request);

    // TODO: Add checks to see if files exist before adding
    $this->link->add('/favicon.ico', 'icon', ['size' => 'any']);
    $this->link->add('/favicon.svg', 'icon', ['type' => 'image/svg+xml']);

    $this->meta->add('viewport', 'width=device-width, initial-scale=1, minimum-scale=1.0, user-scalable=yes');
  }

  public function getBuffer(): string
  {
    $html = '<base href="https://' . $this->conf->site->url . '">';
    $html .= '<title>' . $this->meta->title . ' - ' . $this->conf->site->name . '</title>';

    $html .= $this->meta->getBuffer();
    $html .= $this->link->getBuffer();
    $html .= $this->css->getBuffer();
    $html .= $this->javascript->getBuffer();

    $html = str_replace('><', ">\n  <", $html);

    return $html;
  }
}
