<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Hr extends \Adept\Abstract\Document\HTML\Element
{
	protected bool $close = false;
	protected string $tag = 'hr';
}
