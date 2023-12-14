<?php

namespace Adept\Abstract\Document\HTML\Body\Form;
/*
use \Adept\CMSClass;
use \Adept\HTML\P;
*/


defined('_ADEPT_INIT') or die();

abstract class Element extends \Adept\Abstract\Document\HTML\Body\Element
{
  /**
   * Array of errors
   * 
   * @var array
   */
  protected array $errors = [];
}
