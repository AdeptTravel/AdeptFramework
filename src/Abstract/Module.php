<?php

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die();

abstract class Module
{
  /**
   * The application object
   * 
   * @var \Adept\Application
   */
  protected $app;

  /**
   * The document object
   *
   * @var \Adept\Document\HTML
   */
  protected $doc;

  public function __construct(\Adept\Application &$app, \Adept\Document\HTML &$doc)
  {
    $this->app = $app;
    $this->doc = $doc;
  }

  abstract public function getBuffer(): string;
}
