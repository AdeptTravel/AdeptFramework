<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Media\Audio;
use \Adept\Abstract\Configuration\Media\Image;
use \Adept\Abstract\Configuration\Media\Video;

class Media
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Media\Audio
   */
  public Audio $audio;

  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Media\Image
   */
  public Image $image;

  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Media\Video
   */
  public Video $video;

  public function __construct()
  {
    $this->audio = new Audio();
    $this->image = new Image();
    $this->video = new Video();
  }
}
