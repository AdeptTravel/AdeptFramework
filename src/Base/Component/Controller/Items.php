<?php

namespace AdeptCMS\Base\Component\Controller;

defined('_ADEPT_INIT') or die('No Access');

abstract class Items extends \AdeptCMS\Base\Component\Controller
{
  public function onPost()
  {
    $action = $this->app->session->request->getData()->getString('action', INPUT_POST);
    $json = $this->app->session->request->getData()->getString('json', INPUT_POST);

    if (!empty($action) && !empty($json)) {
      if (is_array($data = json_decode($json))) {
        switch ($action) {
          case 'reorder':

            break;
          case 'publish':
            break;
          case 'unpublish':
            break;
          case 'delete':
            break;
          case 'default':
            // Error
            break;
        }
      }
    }
  }
}
