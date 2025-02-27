<?php

namespace Adept\Data\Item\Media;

defined('_ADEPT_INIT') or die('No Access');

class Video extends \Adept\Data\Item\Media
{
  public string $type  = 'Video';

  protected function getDimensions(string $file): object
  {
    $getID3 = new \getID3();
    $id3 = $getID3->analyze($file);

    return (object)[
      'width'    => $id3['video']['resolution_x'],
      'height'   => $id3['video']['resolution_y'],
      'duration' => floor($id3['playtime_seconds'])
    ];
  }
}
