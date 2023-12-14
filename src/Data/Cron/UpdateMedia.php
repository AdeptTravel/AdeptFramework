<?php

define('_ADEPT_INIT', 1);

require_once('../../vendor/autoload.php');
require_once('../../configuration.php');

$conf = new Configuration();
$app = new \Adept\Cron($conf);

$files = [];


// Create iterators
$directoryIterator = new RecursiveDirectoryIterator(FS_IMG);
$iterator = new RecursiveIteratorIterator($directoryIterator);

// Loop through the files
foreach ($iterator as $fileInfo) {
  // Check if it is a file (not a directory)
  // And check for the file extension
  if (
    $fileInfo->isFile()
    && in_array(pathinfo(
      $fileInfo->getFilename(),
      PATHINFO_EXTENSION
    ), $conf->media->image->formats)
  ) {
    /*
  `type` ENUM('Image', 'Video', 'Audio'),
  `file` VARCHAR(128) NOT NULL,
  `title` VARCHAR(128) NOT NULL,
  `caption` TEXT DEFAULT '',
  `summary` VARCHAR(256) DEFAULT '',
  `description` TEXT DEFAULT '',
  `created` DATETIME DEFAULT NOW(),
  `modified` DATETIME DEFAULT NOW(),
  `status` TINYINT DEFAULT 1,
*/

    $file = str_replace(FS_IMG, 'img/', $fileInfo->getRealPath());
    $size = $fileInfo->getSize();

    $createdDate = new DateTime();
    $createdDate->setTimestamp($fileInfo->getCTime());

    $modifiedDate  = new DateTime();
    $modifiedDate->setTimestamp($fileInfo->getMTime());

    $created = $created->format('Y-m-d H:i:s');
    $modified = $modified->format('Y-m-d H:i:s');

    $obj = $app->db->getObject(
      'SELECT * FROM media WHERE filename = ?',
      [$file]
    );

    if (!isset($obj->file)) {
      // Insert
      echo "  * Inserting $file\n";
      $app->db->insert(
        'INSERT INTO `media` (`type`, `file`, `size`, `created`, `modified`) VALUES (, ?, ?, ?, ?)',
        ['Image', $file, $size, $created, $modified]
      );
    } else {
      // Update
      if (
        $obj->size != $size
        || $obj->created != $created
        || $obj->modified != $modified
      ) {
        echo "  * Updating $file\n";
        $app->db->update(
          'UPDATE `media` SET `size` = ?, `created` = ?, `modified = ? WHERE id = ?',
          [$size, $created, $modified, $obj->id]
        );
      }
    }
  }
}
