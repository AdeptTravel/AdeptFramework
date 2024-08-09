<?php

namespace Adept\Data\Items;

defined('_ADEPT_INIT') or die();

class Menu extends \Adept\Abstract\Data\Items
{

  public string $sort = 'title';

  //protected array $empty = ['image', 'imageAlt', 'fa', 'css'];

  public string $title;
  public string $image;
  public string $imageAlt;
  public string $fa;
  public string $css;
}
