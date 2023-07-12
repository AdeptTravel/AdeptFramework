<?php

namespace AdeptCMS\Base;

defined('_ADEPT_INIT') or die();

abstract class Module
{
  /**
   * The application object
   * 
   * @var \AdeptCMS\Application
   */
  protected $app;

  /**
   * The document object
   *
   * @var \AdeptCMS\Document\HTML
   */
  protected $doc;

  public function __construct(\AdeptCMS\Application &$app, \AdeptCMS\Document\HTML &$doc)
  {
    $this->app = $app;
    $this->doc = $doc;
  }

  abstract public function getHTML(): string;
}
