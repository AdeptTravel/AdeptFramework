<?php

namespace Adept\Abstract\Document\HTML;

use Attribute;

defined('_ADEPT_INIT') or die();

use \Adept\Application;
use \Adept\Document\HTML;

abstract class Elements
{

  /**
   * Undocumented variable
   *
   * @var \Adept\Application
   */
  protected Application $app;

  /**
   * Undocumented variable
   *
   * @var \Adept\Document\HTML
   */
  protected HTML $doc;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $children;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $css = [];

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $data = [];

  public bool $required = false;

  public function __construct(array $attr = [], array $children = [])
  {
    $this->children = $children;

    foreach ($attr as $k => $v) {
      if (property_exists($this, $k)) {
        $this->$k = $v;
      }
    }
  }

  function getBuffer(): string
  {
    $html = '';

    if (!empty($this->children)) {
      for ($i = 0; $i < count($this->children); $i++) {
        $html .= $this->children[$i]->getBuffer();
      }
    }

    return $html;
  }
}
