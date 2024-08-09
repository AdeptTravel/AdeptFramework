<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;

class Media extends \Adept\Abstract\Data\Item
{

  protected array $uniqueKeys = ['file'];

  /**
   * ENUM('Audio', 'Image', 'Video'),
   *
   * @var string
   */
  public string $type;

  /**
   * The mimetype of the file.
   *
   * @var string
   */
  public string $mime;

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
  public int $width = 0;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $height = 0;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $duration = 0;

  /**
   * Filesize
   *
   * @var int
   */
  public int $size = 0;

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


  /**
   * The status of the data object: published, unpublished, trashed, lost, archived, etc.
   *
   * @var int
   */
  public int $status = 1;

  /**
   * The creation date of the file.  On Unix/Linux we can't get the created
   * date so this is the date the record was first stored in the database.
   *
   * @var string
   */
  public string $created;

  /**
   * The modified date of the file, pulled from the filesystem.  Used for
   * verifying file changes.
   *
   * @var string
   */
  public string $modified;

  public function __construct(int|string|object $val = 0, bool $cache = true)
  {
    if (is_string($val)) {
      $this->file = $val;
    }

    parent::__construct($val, $cache);
  }

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
          $this->title = substr($file, strrpos($file, '/') + 1);
          $this->title = substr($this->title, 0, strrpos($this->title, '.'));
        }

        if (empty($this->alias)) {
          $this->alias = substr($this->file, 1);
          $this->alias = substr($this->alias, 0, strrpos($this->alias, '.'));
          $this->alias = strtolower($this->alias);
          $this->alias = str_replace(' ', '-', $this->alias);
        }

        while (strpos($this->alias, '--') !== false) {
          $this->alias = str_replace('--', '-', $this->alias);
        }

        while ($this->aliasExists($this->alias)) {
          if (preg_match('/-(\d+)$/', $this->alias, $matches)) {
            // Increment the number by 1
            $number = (int)$matches[1] + 1;
            // Replace the old number with the new number
            $this->alias = preg_replace('/-\d+$/', '-' . $number, $this->alias);
          } else {
            // Add '-0' to the alias if it doesn't end with a dash and a number
            $this->alias = $this->alias . '-0';
          }
        }

        $this->size      = filesize($file);

        $this->modified  = date("Y-m-d H:i:s", filemtime($file));

        if ($this->type == 'Image') {
          $info = getimagesize($file);
          $this->width  = $info[0];
          $this->height = $info[1];
        } else {
          $getID3 = new \getID3();
          $id3 = $getID3->analyze($file);

          if ($type == 'Video') {
            $this->width  = $id3['video']['resolution_x'];
            $this->height = $id3['video']['resolution_y'];
          }
        }
      }
    }
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
}
