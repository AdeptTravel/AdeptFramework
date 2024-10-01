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
    $app  = Application::getInstance();
    $get  = &$app->session->request->data->get;
    $post = &$app->session->request->data->post;

    if (
      $get->getInt('id') > 0 &&
      $post->getInt('id') > 0 &&
      $get->getInt('id') != $post->getInt('id')
    ) {
      // Shenanigans are at play
      die("You're' being a naughty.");
    }

    $action = $post->getString('action', '');

    // POST

    // Determin if the id is sent via get or post
    $id = (!empty($action))
      ? $post->getInt('id', 0)
      : $get->getInt('id', 0);

    $item = $this->getItem($id);

    if (strpos($action, 'save') !== false) {
      $item->loadFromPost($post);

      if ($id > 0) {
        $item->id = $id;
      }

      if ($item->save()) {
        $this->status->addSuccess('Success', 'The data was successfuly saved.');
      } else {
        for ($i = 0; $i < count($item->error); $i++) {
          $this->status->addError('Error', $item->error[$i]);
        }
      }
    }

    if (!empty($action) && !empty($this->status->success)) {
      $path = '/' . $app->session->request->url->path;
      $path = substr($path, 0, strrpos($path, '/'));

      if ($action == 'close' || $action == 'saveclose') {
        $app->session->request->redirect($path);
      } else if ($action == 'savenew' || $action == 'savecopy') {
        $app->session->request->redirect($path . '/edit');
      }
    }
  }

  abstract function getItem(int $id): \Adept\Abstract\Data\Item;
}
