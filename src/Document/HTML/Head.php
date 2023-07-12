<?php

namespace AdeptCMS\Document\HTML;

defined('_ADEPT_INIT') or die();

class Head
{
  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Base\Configuration
   */
  protected $conf;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Document\HTML\Head\CSS
   */
  public $css;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Document\HTML\Head\JavaScript
   */
  public $javascript;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Document\HTML\Head\Link
   */
  public $link;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Document\HTML\Head\Meta
   */
  public $meta;

  public $title;

  public function __construct(
    \AdeptCMS\Base\Configuration &$conf,
    \AdeptCMS\Application\Session\Request &$request
  ) {
    $this->conf = $conf;
    $this->meta = new \AdeptCMS\Document\HTML\Head\Meta($conf, $request);
    $this->link = new \AdeptCMS\Document\HTML\Head\Link($conf, $request);
    $this->css = new \AdeptCMS\Document\HTML\Head\CSS($conf, $request);
    $this->javascript = new \AdeptCMS\Document\HTML\Head\JavaScript($conf, $request);

    // TODO: Add checks to see if files exist before adding
    $this->link->add('/favicon.ico', 'icon', ['size' => 'any']);
    $this->link->add('/favicon.svg', 'icon', ['type' => 'image/svg+xml']);

    $this->meta->add('viewport', 'width=device-width, initial-scale=1, minimum-scale=1.0, user-scalable=yes');
  }

  public function getHTML(): string
  {
    $html = '<base href="https://' . $this->conf->site->url . '">';
    $html .= '<title>' . $this->meta->title . ' - ' . $this->conf->site->name . '</title>';

    $html .= $this->meta->getHTML();
    $html .= $this->link->getHTML();
    $html .= $this->css->getHTML();
    $html .= $this->javascript->getHTML();

    $html = str_replace('><', ">\n  <", $html);

    return $html;
  }
}
