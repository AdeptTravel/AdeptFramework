<?php

namespace AdeptCMS\Document\HTML\Body\Element\DataList;

defined('_ADEPT_INIT') or die();

class Airport extends \AdeptCMS\Document\HTML\Body\Element\DataList
{
  public function getOptions(): array
  {

    $query = "SELECT a.iata AS `value`, CONCAT_WS(', ', a.iata, a.airport, b.city, b.region, b.country) AS title";
    //$query = "SELECT a.id AS `value`, CONCAT_WS(', ', a.iata, a.airport) AS title";
    //$query = "SELECT a.iata AS `value`, CONCAT_WS(', ', a.iata, a.airport) AS title";
    $query .= " FROM airport AS a";
    $query .= " INNER JOIN address AS b ON a.address = b.id";
    $query .= " ORDER BY a.airport ASC";

    $result = $this->db->getObjects($query, []);

    return $result;
  }
}
