<?php

namespace AdeptCMS\Document\HTML\Body\Element\Select;

defined('_ADEPT_INIT') or die();

class ContentParent extends \AdeptCMS\Document\HTML\Body\Element\Select
{
  //$html .= ' data-field-type="route"';

  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Application\Session &$session,
    \AdeptCMS\Document\HTML\Head &$head,
    object &$element,
    string $alias = ''
  ) {
    
    parent::__construct($db, $session, $head, $element, $alias);

    $query  = "WITH RECURSIVE `a` (`id`, `parent`, `title`, `path`) AS";
    $query .= "(";
    $query .= " SELECT `id`, `parent`, `title`, `title` as `path`";
    $query .= " FROM `content`";
    $query .= " WHERE `parent` = 0 AND `type` = 'Category'";
    $query .= " UNION ALL";
    $query .= " SELECT `c`.`id`, `c`.`parent`, `c`.`title`, CONCAT(`b`.`path`, '/', `c`.`title`)";
    $query .= " FROM `a` AS `b` JOIN content AS `c`";
    $query .= " ON `b`.`id` = `c`.`parent`";
    $query .= " WHERE `c`.`type` = 'Category'";
    $query .= ")";
    $query .= " SELECT `id` AS `value`, `path` AS `title` FROM `a`";
    $query .= " ORDER BY `path`";

    $result = $this->db->getObjects($query, []);

    if ($result !== false) {

      // Add first element
      array_unshift($result, (object)[
        'value' => 0,
        'title' => '--No Parent --',
      ]);
    } else {
      $result = [(object)[
        'value' => 0,
        'title' => '--No Parent --',
      ]];
    }

    $this->options = $result;
  }
}
