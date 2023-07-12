<?php

namespace AdeptCMS\Base\Component\View;

defined('_ADEPT_INIT') or die('No Access');

class HTML extends \AdeptCMS\Base\Component\View
{
  use \AdeptCMS\Traits\FileSystem;

  /**
   * @var string
   */
  public $template = '';

  /**
   * @var \AdeptCMS\Document\HTML
   */
  protected $doc;

  public function __construct(\AdeptCMS\Application &$app, \AdeptCMS\Document\HTML &$doc)
  {
    $this->app = $app;
    $this->doc = $doc;

    $this->params = [];

    $this->load();
  }

  protected function load()
  {
  }

  public function getBuffer(string $template = ''): string
  {
    $route = $this->app->session->request->route;
    $area = $route->area;
    $component = $route->component;

    if (empty($template) && !empty($this->template)) {
      $template = $this->template;
    } else if (empty($template) && empty($this->template) && !empty($route->template)) {
      $template = $route->template;
    } else if (empty($template) && empty($this->template) && empty($route->template) && !empty($route->option)) {
      $template = $route->option;
    } else if (empty($template)) {
      $template = 'Default';
    }

    $file  = $this->matchPath(FS_COMPONENT . $component . '/' . $area . '/View/HTML/Template/' . $template . '.php');

    if ($file === false) {

      echo '<p>File ' . $file . ' not found</p>';

      // TODO: Error, no template
      throw new \AdeptCMS\Exceptions\Format\Unsupported(
        'File Format/Type Unsupported',
        "The file format 'HTML' is unsupported.",
        __NAMESPACE__,
        __CLASS__,
        __METHOD__
      );
    }

    $css = FS_COMPONENT . $component . '/' . $area . '/Template/CSS/' . $template . '.css';

    if (file_exists($css)) {
      $this->addCSS(file_get_contents($css));
    }

    $js = FS_COMPONENT . $component . '/' . $area . '/Template/JavaScript/' . $template . '.js';

    if (file_exists($js)) {
      $this->addJavaScript(file_get_contents($js));
    }

    $html = '';

    ob_start();

    include($file);

    $html = ob_get_contents();

    ob_end_clean();

    return $html;
  }

  public function addCSS(string $css)
  {
    $this->doc->head->css->addCSS($css);
  }

  public function addCSSFile(string $file)
  {
    $this->doc->head->css->addFile($file);
  }

  public function addJavaScript(string $js)
  {
    $this->doc->head->javascript->addJavaScript($js);
  }

  public function addJavaScriptFile(string $file)
  {
    $this->doc->head->javascript->addFile($file);
  }

  public function addLink(string $href, string $rel, array $args = [])
  {
    $this->doc->head->link->add($href, $rel, $args);
  }

  public function addMeta(string $name, string $content)
  {
    $this->doc->head->meta->add($name, $content);
  }

  public function loadSubTemplate(string $name, array|object $data = []): string
  {
    $out = '';
    $route = $this->app->session->request->route;
    $area = $route->area;
    $component = $route->component;

    $template = $this->template . '/' . $name;

    $file  = FS_COMPONENT . $component . '/' . $area . '/Template/' . $template . '.php';

    if (file_exists($file)) {
      ob_start();
      include $file;
      $out = ob_get_clean();
    }

    return $out;
  }
}
