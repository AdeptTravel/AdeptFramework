<?php

namespace Adept\Component\CMS\Media\HTML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Document\HTML;
use \Adept\Application;

class Image extends \Adept\Abstract\Component
{

  /**
   * Undocumented function
   *
   * @param  \Adept\Application   $app
   * @param  \Adept\Document\HTML $doc
   */

  public function __construct(Application &$app, HTML &$doc)
  {
    parent::__construct($app, $doc);

    $path = FS_MEDIA . \urldecode($app->session->request->data->get->getString('path', ''));

    $this->data = new \Adept\Data\Items\Image($app->db);
    $this->data->type = 'Image';
    $this->data->path = 'Image/Today in Travel';
    $this->data->load();
  }
}
