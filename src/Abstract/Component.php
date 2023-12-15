<?php

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application;
use \Adept\Abstract\Data\Item;
use \Adept\Abstract\Data\Items;
use \Adept\Abstract\Document;
use \Adept\Application\Session\Request\Data\Get;
use \Adept\Application\Session\Request\Data\Post;
use \Adept\Document\HTML\Body\Status;

class Component
{
  /**
   * A reference to the Application object
   *
   * @var \Adept\Application
   */
  protected Application $app;

  /**
   * A reference to the Document object
   *
   * @var \Adept\Abstract\Document
   */
  protected Document $doc;

  /**
   * Undocumented variable
   *
   * @var array|object
   */
  public array|object $data;

  /**
   * @var \Adept\Abstract\Data\Item
   */
  public Item $item;

  /**
   * @var \Adept\Abstract\Data\Items
   */
  public Items $items;

  /**
   * Status messages
   *
   * @var \Adept\Document\HTML\Body\Status
   */
  public Status $status;

  public function __construct(Application &$app, Document &$doc)
  {
    $this->app = $app;
    $this->doc = $doc;

    if ($this->app->session->request->url->type == 'HTML') {
      $this->status = new Status();
    }

    /*
    $get     = $this->app->session->request->get;
    $post    = $this->app->session->request->post;
    $session = $this->app->session->data;
    */
  }

  public function getBuffer(string $template = ''): string
  {
    $buffer     = '';
    $category   = $this->app->session->request->route->category;
    $component  = $this->app->session->request->route->component;
    $option     = $this->app->session->request->route->option;
    $type       = $this->app->session->request->url->type;

    if (!empty($template)) {
      $template = '_' . $template;
    }

    // Loaded the base class, now we need to find the template file.
    if (
      $type == 'HTML'
      && (file_exists($file = FS_SYS_COMPONENT . "$category/$component/$type/Template/$option$template.php")
        || file_exists($file = FS_SITE_COMPONENT . "$category/$component/$type/Template/$option$template.php"))
    ) {
      ob_start();

      include($file);

      $buffer = ob_get_contents();

      ob_end_clean();
    } else {
      $method = 'get' . $this->app->session->request->url->type;

      if (method_exists($this, $method)) {
        $buffer = $this->$method();
      }
    }

    return $buffer;
  }

  // This is here for maybe loading sub-templates from a template file.  Might
  // just get rid of it, not sure yet.
  public function getHTMLTemplate(string $template): string
  {
    $buffer     = '';
    $category   = $this->app->session->request->route->category;
    $component  = $this->app->session->request->route->component;
    $option     = $this->app->session->request->route->option;
    $type       = $this->app->session->request->url;

    if (
      file_exists($file = FS_SYS_COMPONENT . "$category/$component/$type/Template/$option$template.php")
      || file_exists($file = FS_SITE_COMPONENT . "$category/$component/$type/Template/$option$template.php")
    ) {
      ob_start();

      include($file);

      $buffer = ob_get_contents();

      ob_end_clean();
    }

    return $buffer;
  }

  public function getJSON(): string
  {
    $json = '';

    if (isset($this->data)) {
      $json = json_encode($this->data);
    } else if (!empty($this->items)) {
      $json = json_encode($this->items);
    } else if (!empty($this->item)) {
      $json = json_encode($this->item);
    }

    return $json;
  }


  public function onRenderStart()
  {
  }

  public function onRenderEnd()
  {
  }
}
