<?php

namespace AdeptCMS\Base\Model;

defined('_ADEPT_INIT') or die();

abstract class Items extends \AdeptCMS\Base\Component\Model
{
  /**
   * filters
   *
   * @var array
   */
  protected $_filter;

  /**
   * Database Table
   *
   * @var string
   */
  protected $_table;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected $_data;

  public function __construct(\AdeptCMS\Application\Database &$db, array $filter = [])
  {
    parent::__construct($db);

    $this->_filter = $filter;
  }

  public function getLevel(array &$data, int $parent): array
  {
    $result = [];

    foreach ($data as $k => $d) {
      if ($d->parent == $parent) {
        $result[] = $d;
        $d->children = $this->getLevel($data, $d->id);
        unset($d);
      }
    }

    return $result;
  }

  public function reorder(array $keys)
  {
    for ($i = 0; $i < count($keys); $i++) {
      //$log = "UPDATE `" . $table . "` SET `order` = ? WHERE `id` = ?\n\n" . print_r([$i, $keys[$i]], true) . "\n\n";
      //file_put_contents(FS_LOG . 'listing.reorder.log', $log, FILE_APPEND);

      $this->_db->update(
        "UPDATE `" . $this->_table . "` SET `order` = ? WHERE `id` = ?",
        [$i, $keys[$i]]
      );
    }
  }

  public function publish(array $keys)
  {
    for ($i = 0; $i < count($keys); $i++) {
      $this->_db->update(
        "UPDATE `" . $this->_table . "` SET `publish` = 1 WHERE `id` = ?",
        [$keys[$i]]
      );
    }
  }

  public function unpublish(array $keys)
  {
    for ($i = 0; $i < count($keys); $i++) {
      $this->_db->update(
        "UPDATE `" . $this->_table . "` SET `publish` = 0 WHERE `id` = ?",
        [$keys[$i]]
      );
    }
  }

  public function delete(array $keys)
  {
    for ($i = 0; $i < count($keys); $i++) {
      $this->_db->update(
        "DELETE FROM  `" . $this->_table . "` WHERE `id` = ?",
        [$keys[$i]]
      );
    }
  }

  public function getData(): array
  {
    return $this->_data;
  }

  public function setFilter(string $key, string|int|bool $value)
  {
    $this->_filter[$key] = $value;
  }

  public function getFilter(string $key): string|int|bool
  {
    return $this->_filter[$key];
  }
}
