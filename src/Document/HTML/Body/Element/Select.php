<?php

namespace AdeptCMS\Document\HTML\Body\Element;

defined('_ADEPT_INIT') or die();

class Select extends \AdeptCMS\Base\Document\HTML\Body\Form\Element
{
  /**
   * Undocumented variable
   *
   * @var array
   */
  public $values;

  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Application\Session &$session,
    \AdeptCMS\Document\HTML\Head &$head,
    object &$element,
    string $alias = ''
  ) {

    parent::__construct($db, $session, $head, $element, $alias);

    if (empty($this->values) && !empty($element->options)) {
      $this->options = $element->options;
    }
  }
}
