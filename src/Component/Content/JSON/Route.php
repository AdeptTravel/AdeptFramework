<?php

namespace Adept\Component\Content\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;


class Route extends \Adept\Abstract\Component\JSON
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    $app  = Application::getInstance();

    $parent = $app->session->request->data->post->getInt('parent');
    $title  = $app->session->request->data->post->getString('title');
    $route  = '';
    //$query = 'SELECT route FROM Route';

    if ($parent > 0) {
      $query  = 'SELECT r.route';
      $query .= ' FROM Content AS c';
      $query .= ' INNER JOIN Route AS r ON c.route = r.id';
      $query .= ' WHERE c.id = ?';
      $param = [$parent];

      $route = $app->db->getString($query, $param);
    }

    $title = strip_tags($title);
    $title = addslashes($title);
    $title = str_replace(' ', '-', $title);
    $title = preg_replace('/[^a-zA-Z0-9-]/', '', $title);
  }
}
