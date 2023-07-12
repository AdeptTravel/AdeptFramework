<?php

namespace AdeptCMS\Base\Document\HTML\Head;

defined('_ADEPT_INIT') or die();

class Asset
{
  use \AdeptCMS\Traits\FileSystem;


  protected $cache;

  /**
   * Reference to the global configuration object
   *
   * @var \AdeptCMS\Base\Configuration
   */
  protected $conf;

  /**
   * Extension (ie css, js, etc.)
   *
   * @var string
   */
  protected $extension;

  /**
   * Object that contains two arrays for both local and forign files
   *
   * @var object
   */
  protected $files;

  /**
   * Inline data
   *
   * @var array
   */
  protected $inline;

  /**
   * Reference to the Request object
   *
   * @var \AdeptCMS\Application\Session\Request
   */
  protected $request;

  /**
   * Asset type ie. CSS, JavaScript, etc.
   *
   * @var string
   */
  protected $type;

  /**
   * Absolute path to the template directory
   *
   * @var string
   */
  protected $template;

  /**
   * Init
   *
   * @param \AdeptCMS\Base\Configuration $conf
   * @param \AdeptCMS\Application\Session\Request $request
   */
  public function __construct(
    \AdeptCMS\Base\Configuration &$conf,
    \AdeptCMS\Application\Session\Request &$request
  ) {
    $classname = get_class($this);

    $this->request = $request;
    $this->files = new \stdClass();
    $this->files->foreign = [];
    $this->files->local = [];
    $this->inline = [];
    $this->type = substr($classname, strrpos($classname, '\\') + 1);
    $this->template = $request->route->area;

    $extension = '';

    $template = FS_TEMPLATE . $this->template . '/' . $this->type . '/';

    if (file_exists($template)) {
      switch ($this->type) {
        case 'CSS':
          $extension = 'css';
          break;

        case 'JavaScript':
          $extension = 'js';
          break;

        default:
          break;
      }

      $this->extension = $extension;
      $this->conf = $conf->optimize->$extension;

      // Autoload base asset files
      //if ($request->route->area != 'Admin' || ) {

      if ($request->route->route == 'login' || $request->route->route == 'admin/login') {
        if ($this->type == 'CSS' && file_exists($template . '00-Globals.css')) {
          $this->addFile($template . '00-Globals.css');
        }
      } else {
        foreach (scandir($template) as $file) {
          if ($file == '.' || $file == '..' || is_dir($template . $file)) {
            continue;
          }

          if (substr($file, - (strlen($extension))) == $extension) {
            $this->addFile($template . $file);
          }
        }
      }
      // Autoload component asset files
      $component = $this->request->route->component;
      $option = $this->request->route->option;
      $path = $template . 'Component/';

      $files = [
        $this->matchFile($path, $component . '.' . $extension),
        $this->matchFile($path . $component . '/', $option . '.' . $extension)
      ];

      foreach ($files as $file) {
        if (!empty($file) && file_exists($file)) {
          $this->addFile($file);
        }
      }
    }
  }

  public function addInline(string $content)
  {
    $this->inline[] = $content;
  }

  public function addFile(string $file, array $args = [])
  {
    $absolute = (strpos($file, FS_PATH) !== false);
    $local = (substr($file, 0, 4) != 'http' && substr($file, 0, 2) != '//');

    if (!$absolute && $local) {
      /*
      if (($asset = $this->matchAsset(
        FS_TEMPLATE . $this->template . '/' . $this->type . '/', // Template
        $file,
        $this->request->route->area, // Area
        $this->type
      )) !== false) {

        $file = $asset->file;
      }
      */
    }

    if (!empty($file)) {
      if (
        ($local && !array_key_exists($file, $this->files->local))
        || (!$local && !array_key_exists($file, $this->files->foreign))
      ) {

        $asset = new \stdClass();
        $asset->file = $file;

        $asset->args = new \stdClass();

        foreach ($args as $key => $value) {
          $asset->args->$key = $value;
        }

        if ($local) {
          $this->files->local[$file] = $asset;
        } else {
          $this->files->foreign[$file] = $asset;
        }
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

  public function getHTML(): string
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
      $file = $asset->file;
      $file = $this->absToRel($file);

      // The the position of the last occurance of a slash
      $pos = strrpos($file, '/') + 1;

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
    $template = FS_TEMPLATE . $this->template . '/' . $this->type . '/';

    $paths = [
      FS_ASSET . $this->request->route->area . '/' . $this->type . '/',
      FS_CACHE . $this->request->route->area . '/' . $this->type . '/',
      FS_MEDIA . $this->type . '/',
      $template
    ];

    $rel = '/' . $this->extension . '/';

    if ($this->request->route->area == 'Admin') {
      $rel = '/admin' . $rel;
    }
    // Replace absolute paths with relative path
    foreach ($paths as $path) {
      $file = str_replace($path, $rel, $file);
    }
    // Convert to lowercase
    //$file = strtolower($file);

    return $file;
  }
}
