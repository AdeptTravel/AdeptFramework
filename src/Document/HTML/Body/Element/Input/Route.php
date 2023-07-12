<?php

namespace AdeptCMS\Document\HTML\Body\Element\Input;

defined('_ADEPT_INIT') or die();

class Route extends \AdeptCMS\Document\HTML\Body\Element\Input
{
  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Application\Session &$session,
    \AdeptCMS\Document\HTML\Head &$head,
    object &$element,
    string $alias = ''
  ) {
    echo "\n\n<!-- Working -->\n\n";
    parent::__construct($db, $session, $head, $element, $alias);

    $this->head->javascript->addFile('/admin/js/form/input/route.js');
  }
}
