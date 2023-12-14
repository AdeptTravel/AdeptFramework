<?php

namespace Adept\Abstract\Data\Items;

defined('_ADEPT_INIT') or die();

require_once(FS_PATH . '/vendor/james-heinrich/getid3/getid3/getid3.php');

use \Adept\Application\Database;

class Media extends \Adept\Abstract\Data\Items
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
  public string $path;

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
  public int $duraction;

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

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $description;

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
   * Undocumented variable
   *
   * @var bool
   */
  public bool $status = true;

  public function load(): bool
  {

    if (empty($this->path)) {
      $this->path = $this->type;
    }

    // Get files from the database
    $status = parent::load();
    $reload = false;
    //die('<pre>' . print_r($this->items, true));
    // Get new files uploaded to the users media collection

    $files = new \DirectoryIterator(FS_MEDIA . $this->path);

    foreach ($files as $file) {
      $filename = $this->path . '/' . $file->getFilename();
      $mime = ucfirst(substr(mime_content_type(FS_MEDIA . $filename), 0, 5));

      if (
        !$file->isDot()
        && $file->isFile()
        && in_array($mime, ['Audio', 'Image', 'Video'])
        && !$this->inItems('file', $filename)
      ) {

        $namespace = "\\Adept\\Data\\Item\\" . $mime;
        $media = new $namespace($this->db);
        $media->load($filename, 'file');

        if ($media->id > 0) {
          $reload = true;
        }
      }
    }

    if ($reload) {
      $status = parent::load();
    }
    //die();
    return $status;
  }
}
