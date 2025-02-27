<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;

class Media extends \Adept\Abstract\Data\Item
{

  protected string $table      = 'Media';
  protected string $index      = 'file';
  protected array  $uniqueKeys = ['file'];

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
  public string $file;

  /**
   * A websafe version of the filename.  For example 'This is an Image.png'
   * would become 'this_is_an_image'.  Used for naming optimized version of the
   * file.
   *
   * @var string
   */
  public string $alias;

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

  public string $status = 'Active';

  public function loadInfo()
  {
    if (strpos($this->file, FS_SITE_MEDIA) !== false) {
      $this->file = str_replace(FS_SITE_MEDIA . $this->type, '', $this->file);
    }

    $file = FS_SITE_MEDIA . $this->type . $this->file;

    if (file_exists($file)) {

      $this->extension = substr($file, strrpos($file, '.') + 1);

      // Get mimetype of file
      $info = finfo_open(FILEINFO_MIME_TYPE);
      $this->mime = finfo_file($info, $file);
      finfo_close($info);

      $type = ucfirst(substr($this->mime, 0, 5));

      if ($type == $this->type) {

        $this->path = substr($this->file, 0, strrpos($this->file, '/'));

        if (empty($this->title)) {
          $this->title = $this->generateTitle($this->file);
        }

        if (empty($this->alias)) {
          $this->alias = $this->generateAlias($this->file);
        }

        $this->size      = filesize($file);
        $this->updatedAt = \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", filemtime($file)));
        //$this->updatedAt = date("Y-m-d H:i:s", filemtime($file));

        $dimensions   = $this->getDimensions($file);
        $this->width  = $dimensions->width;
        $this->height = $dimensions->height;
      }
    }
  }

  protected function generateTitle(string $file): string
  {
    $title = substr($file, strrpos($file, '/') + 1);
    $title = substr($title, 0, strrpos($title, '.'));
    return $title;
  }

  protected function generateAlias(string $file): string
  {
    $alias = substr($file, 1);
    $alias = substr($alias, 0, strrpos($alias, '.'));
    $alias = strtolower($alias);
    $alias = str_replace(' ', '-', $alias);

    while (strpos($alias, '--') !== false) {
      $alias = str_replace('--', '-', $alias);
    }

    while ($this->aliasExists($alias)) {
      if (preg_match('/-(\d+)$/', $alias, $matches)) {
        // Increment the number by 1
        $number = (int)$matches[1] + 1;
        // Replace the old number with the new number
        $alias = preg_replace('/-\d+$/', '-' . $number, $alias);
      } else {
        // Add '-0' to the alias if it doesn't end with a dash and a number
        $alias = $alias . '-0';
      }
    }

    return $alias;
  }

  protected function aliasExists(string $alias): bool
  {
    $app = \Adept\Application::getInstance();

    $status = true;

    if ($this->id > 0) {
      $query = 'SELECT COUNT(*) FROM Media WHERE alias = ? AND id <> ?';
      $param = [$alias, $this->id];
    } else {
      $query = 'SELECT COUNT(*) FROM Media WHERE alias = ?';
      $param = [$alias];
    }

    $count = $app->db->getInt($query, $param);

    if ($count !== false) {
      $status = ($count > 0);
    }

    return $status;
  }

  protected function getDimensions(string $file): object
  {
    return (object)[
      'width' => 0,
      'height' => 0,
      'duration' => 0
    ];
  }
}
