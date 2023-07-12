<?php

namespace AdeptCMS\Data\Items;

defined('_ADEPT_INIT') or die();

class Route extends \AdeptCMS\Base\Data\Items
{
  public function __construct(
    \AdeptCMS\Application\Database &$db,
    int|string $id = 0,
    array $filter = []
  ) {
    parent::__construct($db, $id, $filter);
  }

  /*
  public function getData(): array
  {

    if (!isset($this->data)) {

      $this->cache = hash('md5', __CLASS__ . json_encode($this->filter));

      if (!$this->loadCache()) {
        $query = 'SELECT * FROM `route`';
        $params = [];

        if (count($this->filter) > 0) {
          $and = false;

          $cols = ['id', 'route', 'type', 'area', 'publish'];
          $offset = 0;
          $limit = 25;

          if (array_key_exists('page', $this->filter) && is_numeric($this->filter['page'])) {
            $page = (int)$this->filter['page'];
            unset($this->filter['page']);

            $offset = $page * $limit;
          }

          if (array_key_exists('limit', $this->filter) && is_numeric($this->filter['limit'])) {
            $limit = (int)$this->filter['limit'];
            unset($this->filter['limit']);
          }

          foreach ($this->filter as $key => $value) {
            $query .= ($and) ? ' AND ' : ' WHERE ';

            if (in_array($key, $cols)) {
              $query .= '`' . $key . '` = ?';
              $params[] = $value;
            } else {
              $query .= '`params` LIKE ?';
              $params[] = '%"' . $key . '":"' . $value . '"%';
            }

            $and = true;
          }

          if ($limit > 0) {
            $query .= ' LIMIT ' . $offset . ',' . $limit;
            // $params[] = (int)$offset;
            // $params[] = (int)$limit;
          }
        }

        //die('Query: ' . $query);
        if (($data = $this->db->getObjects($query, $params)) !== false) {

          for ($i = 0; $i < count($data); $i++) {
            $data[$i]->params = json_decode($data[$i]->params);
          }

          $this->data = $data;
        }
      }
    }

    return $this->data;
  }


  public function isEmpty(): bool
  {
    return empty($this->data);
  }
  */
}
