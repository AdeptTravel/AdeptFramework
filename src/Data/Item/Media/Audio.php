<?php

namespace Adept\Data\Item\Media;

defined('_ADEPT_INIT') or die('No Access');

class Audio extends \Adept\Data\Item\Media
{
  public string $type  = 'Audio';

  protected function getDimensions(string $file): object
  {
    $getID3 = new \getID3();
    $id3 = $getID3->analyze($file);

    return (object)[
      'width'    => 0,
      'height'   => 0,
      'duration' => floor($id3['playtime_seconds'])
    ];
  }
}
