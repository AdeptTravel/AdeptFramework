<?php

namespace Adept\Abstract\Data\Item;

use Adept\Application\Database;
use DateTime;

defined('_ADEPT_INIT') or die('No Access');

class Media extends \Adept\Abstract\Data\Item
{
  protected string $name = 'Media';
  protected string $table = 'media';

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
  public string $mime;

  /**
   * The relative path of the file in the users media collection
   *
   * @var string
   */
  public string $path;

  /**
   * The file of the media within the path of the users media collection
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
   * Width in Pixels (For images and video)
   *
   * @var int
   */
  public int $width;

  /**
   * Height in Pixels (For images and video)
   *
   * @var int
   */
  public int $height;

  /**
   * Duration of the Audio or Video in seconds
   *
   * @var int
   */
  public int $duration;

  /**
   * The filesize in bytes
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

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $description;

  /**
   * Content status, use -1 to show all
   *
   * @var bool
   */
  public bool $status = true;

  /**
   * Undocumented variable
   *
   * @var \DateTime
   */
  public \DateTime $created;

  /**
   * Undocumented variable
   *
   * @var \DateTime
   */
  public \DateTime $modified;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  int                         $id
   */
  public function __construct(Database $db, int $id = 0)
  {
    $this->excludeKeys = ['files'];

    $this->excludeKeysOnNew = [
      'created',
      'modified'
    ];

    parent::__construct($db, $id);
  }

  public function load(int|string $id, string $col = 'id'): bool
  {
    $status =  parent::load($id, $col);

    if (!$status && $col == 'file') {
      $file = $id;
      $path = substr($file, 0, strrpos($file, '/'));

      $fullpath = FS_MEDIA . $file;
      $extension = substr($file, strrpos($file, '.') + 1);

      // Get mimetype of file
      $info = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($info, $fullpath);
      finfo_close($info);

      $type = ucfirst(substr($mime, 0, 5));

      if (
        file_exists($fullpath)
        && $type == $this->type
      ) {

        $getID3 = new \getID3();
        $id3 = $getID3->analyze($fullpath);

        $title = substr($file, strrpos($file, '/') + 1);
        $title = substr($title, 0, strrpos($title, '.'));

        //$alias = str_replace(FS_MEDIA . 'Image/', FS_IMG, $file);
        $alias = substr($file, 6);
        $alias = substr($alias, 0, strrpos($alias, '.'));
        $alias = strtolower($alias);
        $alias = str_replace(' ', '-', $alias);

        while (strpos($alias, '--') !== false) {
          $alias = str_replace('--', '-', $alias);
        }

        $this->mime       = $mime;
        $this->path       = $path;
        $this->file       = $file;
        $this->alias      = $alias;
        $this->extension  = $extension;
        $this->width      = $id3['video']['resolution_x'];
        $this->height     = $id3['video']['resolution_y'];
        $this->size       = $id3['filesize'];
        $this->title      = $title;
        $this->created    = new \DateTime();
        $this->modified   = new \DateTime();

        $this->created->setTimestamp(filectime($fullpath));
        $this->modified->setTimestamp(filemtime($fullpath));

        $status = $this->save();
      }
    }

    return $status;
  }

  public function save(string $table = ''): bool
  {
    return parent::save();
  }
}
