<?php

namespace Adept\Data\Table;

use Adept\Application;

defined('_ADEPT_INIT') or die();

class Media extends \Adept\Abstract\Data\Table
{
  protected string $table     = 'Media';
  protected array $uniqueKeys = ['file'];
  protected array $like       = ['file'];

  public string $sort = 'file';

  /**
   * ENUM('Audio', 'Image', 'Video'),
   *
   * @var string
   */
  public string $type;

  /**
   * The relative path to the file.  Used for filtering the list view.
   *
   * @var string
   */
  public string $path;

  /**
   * The relative path + the file name.
   *
   * @var string
   */
  public string $file = '';

  /**
   * A websafe version of the filename.  For example 'This is an Image.png'
   * would become 'this_is_an_image'.  Used for naming optimized version of the
   * file.
   *
   * @var string
   */
  public string $alias = '';

  /**
   * The mimetype of the file.
   *
   * @var string
   */
  public string $mime;

  /**
   * The files extension, used when creating optimized versions of the file.
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
   * Filesize
   *
   * @var int
   */
  public int $size;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $title = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $caption = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $summary = '';


  public function getDirs(): array
  {
    $dirs = [];
    $path = FS_SITE_MEDIA . $this->type . $this->path;

    if (file_exists($path)) {
      $iterator = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);

      foreach ($iterator as $info) {
        if ($info->isDir()) {
          $dirs[] = $info->getFilename();
        }
      }

      sort($dirs);
    } else {

      $db = Application::getInstance()->db;
      $db->update(
        "UPDATE `Media` SET `Status` = 'Trash' WHERE `path` LIKE ?",
        [$this->path . '%']
      );
    }

    return $dirs;
  }

  public function load()
  {
    $path = FS_SITE_MEDIA . $this->type . $this->path;

    if (file_exists($path)) {

      $iterator = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);
      $files = [];

      foreach ($iterator as $info) {

        if ($info->isFile()) {
          $file = $this->path . '/' . $info->getFilename();
          $media = $this->getItem();
          $media->loadFromIndex($file);



          if ($media->id == 0) {
            $media->file = $file;
          }

          $media->loadInfo();

          if ($media->hasChanged()) {
            $media->save();
          }

          $files[] = $file;
        }
      }

      $list = $this->getData();

      for ($i = 0; $i < count($list); $i++) {
        if (!in_array($list[$i]->file, $files)) {
          $ns = "\\Adept\\Data\\Item\\Media\\" . $this->type;
          $media = new $ns($list[$i]->file, false);
          $media->status = ITEM_STATUS_MISSING;
          $media->save();
        }
      }
    } else {
      // Path doesn't exist, update database.

      $db = Application::getInstance()->db;
      $db->update(
        "UPDATE `Media` SET `Status` = 'Trash' WHERE `path` LIKE ?",
        [$this->path . '%']
      );
    }
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Media
  {
    $ns = "\\Adept\\Data\\Item\\Media\\" . $this->type;
    $item = new $ns();

    if ($id > 0) {
      $item->loadFromId($id);
    }

    return $item;
  }
}
