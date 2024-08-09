<?php

namespace Adept\Abstract\Document\HTML\Head;

defined('_ADEPT_INIT') or die();

class Asset
{
  /**
   * Undocumented variable
   *
   * @var string
   */
  protected string $cache;

  /**
   * Reference to the global configuration object
   *
   * @var \Adept\Abstract\Configuration\Assets\Asset
   */
  protected \Adept\Abstract\Configuration\Assets\Asset $conf;

  /**
   * Extension (ie css, js, etc.)
   *
   * @var string
   */
  protected string $extension;

  /**
   * Object that contains two arrays for both local and forign files
   *
   * @var object
   */
  protected object $files;

  /**
   * Inline data
   *
   * @var array
   */
  protected array $inline;

  /**
   * Absolute ath to the asset directory
   *
   * @var string
   */
  protected string $path;

  /**
   * Asset type ie. CSS, JavaScript, etc.
   *
   * @var string
   */
  protected string $type;

  /**
   * Absolute path to the template directory
   *
   * @var string
   */
  protected string $template;

  /**
   * Init
   *
   * @param \Adept\Abstract\Configuration $conf
   * @param \Adept\Application\Session\Request $request
   */
  public function __construct()
  {
    $app       = \Adept\Application::getInstance();
    $classname = get_class($this);
    $type      = substr($classname, strrpos($classname, '\\') + 1);
    $ext       = $this->extension;

    $this->files          = new \stdClass();
    $this->files->foreign = [];
    $this->files->local   = [];
    $this->inline         = [];
    $this->type           = $type;
    $this->template       = $app->session->request->route->template;
    $this->conf           = $app->conf->assets->$ext;

    // Autoload base asset files files
    if ($this->conf->autoload) {

      if (file_exists($file = $this->path . 'template.' . strtolower($this->template) . '.' . $this->extension)) {
        $this->addFile($file);
      }

      foreach (scandir($this->path) as $file) {
        if ($file == '.' || $file == '..' || is_dir($this->path . $file)) {
          continue;
        }

        if (preg_match('/^\d{2}-/', $file)) {
          $this->addFile($this->path . $file);
        }
      }

      $fileComponent = strtolower($this->path . 'component/' .
        $app->session->request->route->component . '/' .
        $app->session->request->route->option . '.' . $this->extension);

      if (file_exists($fileComponent)) {
        $this->addFile(str_replace(FS_SITE, '', $fileComponent));
      }
    }
  }

  public function addInline(string $content)
  {
    $this->inline[] = $content;
  }

  public function addFile(string $file, array $args = [])
  {



    $absolute = (strpos($file, FS_SITE) !== false);
    $local    = (substr($file, 0, 4) != 'http' && substr($file, 0, 2) != '//');

    if (!$absolute && $local) {

      // Remove the first / if present
      if (substr($file, 0, 1) == '/') {
        $file = substr($file, 1);
      }

      if (file_exists($this->path . $file)) {
        $file = $this->path . $file;
      } else {
        $file = '';
      }
    }

    if (!empty($file)) {
      $asset = new \stdClass();
      $asset->file = $file;

      $asset->args = new \stdClass();

      foreach ($args as $key => $value) {
        $asset->args->$key = $value;
      }

      if ($local && !array_key_exists($file, $this->files->local)) {
        $this->files->local[$file] = $asset;
      } else if (!$local && !array_key_exists($file, $this->files->foreign)) {
        $this->files->foreign[$file] = $asset;
      }
    }
  }

  public function combine(): string
  {
    $seed = '';
    $time = 0;

    foreach ($this->files->local as $file) {
      $seed .= $file . '-';

      if (($newer = filemtime($file)) > $time) {
        $time = filemtime($file);
      }
    }

    $hash = hash('md5', $seed);
    $cache = $this->cache . $hash . '.' . $this->extension;

    if (!file_exists($cache) || $time > filemtime($cache)) {
      $data = '';

      foreach ($this->files->local as $file) {
        $data .= file_get_contents($file);
      }

      if ($this->conf->minify) {
        $data = $this->minify($data);
      }

      file_put_contents($cache, $data);
    }

    return $cache;
  }

  public function minify(string $content): string
  {
    return '';
  }

  public function formatArgs(\stdClass $args = null): string
  {
    $formatted = '';

    if (isset($args)) {
      $format = '';

      foreach ($args as $key => $value) {
        $format .= $key . '="' . $value . '" ';
      }

      $formatted = ' ' . trim($format);
    }

    return $formatted;
  }

  public function getFileTag(string $file, \stdClass $args = null): string
  {
    return '';
  }

  public function getInlineTag(string $contents, \stdClass $args = null): string
  {
    return '';
  }

  public function getBuffer(): string
  {

    $html = '';

    // Add foreign assets
    foreach ($this->files->foreign as $asset) {
      $html .= $this->getFileTag($asset->file, $asset->args);
    }

    // Add local assets
    /*
    if ($this->conf->combine) {
      $file = $this->combine();
      $file = $this->absToRel($file);
      $html .= $this->getFileTag($file);
    } else {
    */

    foreach ($this->files->local as $asset) {
      $file = '/' . $this->absToRel($asset->file);

      // The the position of the last occurance of a slash
      //$pos = strrpos($file, '/') + 1;

      // Remove load order 
      //if (substr($file, $pos + 2, 1) == '-') {
      //  $file = substr($file, 0, $pos) . substr($file, $pos + 3);
      //}

      // Add local asset
      $html .= $this->getFileTag($file, $asset->args);
    }
    //}

    if (count($this->inline) > 0) {
      $content = '';

      foreach ($this->inline as $inline) {
        $content .= $inline . ((!$this->conf->minify) ? "\n\n" : '');
      }

      if ($this->conf->minify) {
        $content = $this->minify($content);
      }

      $html .= $this->getInlineTag($content);
    }

    return $html;
  }


  public function absToRel(string $file): string
  {
    return str_replace(FS_SITE, '', $file);
  }
}
