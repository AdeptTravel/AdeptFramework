<?php

namespace Adept\Component\Media\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;
use Adept\Document\HTML;
use Adept\Document\HTML\Body\Status;
use Adept\Document\HTML\Head;


class Image extends \Adept\Abstract\Component\HTML\Item
{
  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct(Application &$app, Head &$head)
  {
    parent::__construct($app, $head);
    $head->meta->title = 'Upload ' . ucfirst($app->session->request->url->parts[1]) . ' File';

    //$this->conf->controls->save       = true;
    //$this->conf->controls->saveclose  = true;
    //$this->conf->controls->savecopy   = true;
    //$this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Media
  {
    if ($id == 0) {
      $id = $this->app->session->request->data->get->getInt('id');
    }

    return new \Adept\Data\Item\Media\Image($this->app->db, $id, false);
  }
}
