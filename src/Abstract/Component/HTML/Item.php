<?php

namespace Adept\Abstract\Component\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application;

abstract class Item extends \Adept\Abstract\Component\HTML
{
  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct()
  {
    parent::__construct();
    $app    = \Adept\Application::getInstance();
    $get    = &$app->session->request->data->get;
    $post   = &$app->session->request->data->post;
    $status = null;

    if (
      $get->getInt('id') > 0 &&
      $post->getInt('id') > 0 &&
      $get->getInt('id') != $post->getInt('id')
    ) {
      // Shenanigans are at play
      die("You're' being a naughty.");
    }

    // Determin if the id is sent via get or post
    $id = ($get->getInt('id', 0) > 0)
      ? $get->getInt('id', 0)
      : $post->getInt('id', 0);

    $action = $post->getString('action', '');

    if (strpos($action, 'save') !== false) {
      $item = $this->getItem();
      $item->loadFromPost($post);

      if ($id > 0) {
        $item->id = $id;
      }

      if ($status = $item->save()) {
        $this->status->addInformation('Success', 'The data was successfuly saved.');
      } else {

        for ($i = 0; $i < count($this->item->error); $i++) {
          $this->status->addError('Error', $this->item->error[$i]);
        }
      }
    }
    if (!empty($action) && $status) {

      $path = '/' . $app->session->request->url->path;
      $path = substr($path, 0, strrpos($path, '/'));

      if ($action == 'close' || $action == 'saveclose') {
        $app->session->request->redirect($path);
      } else if ($action == 'savenew' || $action == 'savecopy') {
        $app->session->request->redirect($path . '/edit');
      }
    }
  }

  abstract function getItem(int $id = 0): \Adept\Abstract\Data\Item;
}
