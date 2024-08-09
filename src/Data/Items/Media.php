<?php

namespace Adept\Data\Items;

defined('_ADEPT_INIT') or die();

class Media extends \Adept\Abstract\Data\Items
{
  protected string $errorName = 'Media';
  protected string $table     = 'media';
  public string $sort         = 'title';

  /**
   * ENUM('Audio', 'Image', 'Video'),
   *
   * @var string
   */
  public string $type;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $path = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $file;

  /**
   * The formatted filename
   *
   * @var string
   */
  public string $alias;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $extension;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $width;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $height;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $duration;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $size;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $title;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $caption;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $summary;


  public function getDirs(): array
  {

    $path = FS_SITE_MEDIA . $this->type . $this->path;

    $iterator = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);
    $dirs = [];

    foreach ($iterator as $info) {
      if ($info->isDir()) {
        $dirs[] = $info->getFilename();
      }
    }

    sort($dirs);

    return $dirs;
  }

  public function load()
  {
    $path = FS_SITE_MEDIA . $this->type . $this->path;

    $iterator = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);
    $files = [];

    foreach ($iterator as $info) {

      if ($info->isFile()) {
        $file = $this->path . '/' . $info->getFilename();

        $ns = "\\Adept\\Data\\Item\\Media\\" . $this->type;
        $media = new $ns($file, false);
        $media->loadInfo();

        if ($media->hasChanged()) {
          $media->save();
        }

        $files[] = $file;
      }
    }

    $list = $this->getList();

    for ($i = 0; $i < count($list); $i++) {
      if (!in_array($list[$i]->file, $files)) {
        $ns = "\\Adept\\Data\\Item\\Media\\" . $this->type;
        $media = new $ns($list[$i]->file, false);
        $media->status = ITEM_STATUS_MISSING;
        $media->save();
      }
    }
  }
}
