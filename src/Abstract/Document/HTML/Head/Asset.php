<?php

namespace Adept\Abstract\Document\HTML\Head;

use Adept\Application;

defined('_ADEPT_INIT') or die();

class Asset
{
  /**
   * List of assets to load
   *
   * @var array
   */
  protected array $asset;

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
    $app       = Application::getInstance();
    $classname = get_class($this);
    $type      = substr($classname, strrpos($classname, '\\') + 1);
    $ext       = $this->extension;
    $debug     = Application::getInstance()->debug;

    $this->asset          = [];
    $this->files          = new \stdClass();
    $this->files->foreign = [];
    $this->files->local   = [];
    $this->inline         = [];
    $this->type           = $type;
    $this->template       = $app->session->request->route->template;
    $this->conf           = $app->conf->assets->$ext;

    $this->addAsset('Core/Global');
    $this->addAsset('Template/Template');

    // Autoload base asset files files
    if ($this->conf->autoload) {

      if (file_exists($file = $this->path . 'template.' . strtolower($this->template) . '.' . $this->extension)) {
        $debug->add('Autoload', 'Adding ' . $this->absToRel($file));
        $this->addFile($file);
      }

      foreach (scandir($this->path) as $file) {
        if ($file == '.' || $file == '..' || is_dir($this->path . $file)) {
          continue;
        }

        if (preg_match('/^\d{2}-/', $file)) {
          $debug->add('Autoload', 'Adding ' . $this->absToRel($file));

          //$this->addFile($this->path . $file);
          $this->addFileInline($this->path . $file);
        } else {
          $debug->add('Autoload', 'Not Found ' . $this->absToRel($file));
        }
      }

      //namespace Adept\Component\CMS\Media\Admin\HTML;

      $fileComponent = strtolower($this->path . 'component/' .
        $app->session->request->route->type . '/' .
        $app->session->request->route->component . '/' .
        $app->session->request->route->area . '/' .
        $app->session->request->route->view . '.' . $this->extension);

      if (file_exists($fileComponent)) {
        $debug->add('Autoload', 'Not Found ' . $this->absToRel($fileComponent));
        //$this->addFile(str_replace(FS_SITE, '', $fileComponent));
        $this->addFileInline(str_replace(FS_SITE, '', $fileComponent));
      } else {
        $debug->add('Autoload', 'Not Found ' . $this->absToRel($fileComponent));
      }
    }
  }

  public function addAsset(string $asset)
  {
    if (!array_key_exists($asset, $this->asset)) {
      if (strpos($asset, '/') !== false) {
        $app = Application::getInstance();
        $parts = explode('/', $asset);

        $route = $app->session->request->route;

        // The order we look for is /css or /js, then the template on the site
        // side, then the template on the core side
        $dirs = [];

        if ($this->type == 'CSS') {
          $dirs[] = FS_CSS;
        } else if ($this->type == 'JavaScript') {
          $dirs[] = FS_JS;
        }

        if ($parts[0] == 'Template') {
          $dirs[] = FS_SITE_TEMPLATE . $route->template . '/' . $this->type . '/';
          $dirs[] = FS_CORE_TEMPLATE . $route->template . '/' . $this->type . '/';
        }

        // Next we check what type was specified
        if ($parts[0] == 'Component') {
          $dirs[] = FS_SITE_TEMPLATE . $route->template . '/' . $this->type . '/Component/' . $route->component;
          $dirs[] = FS_CORE_TEMPLATE . $route->template . '/' . $this->type . '/Component/' . $route->component;
          ///////
          $dirs[] = FS_SITE_COMPONENT . $route->type . '/' . $route->component . '/' . $route->area . '/' . $this->type . '/';
          $dirs[] = FS_CORE_COMPONENT . $route->type . '/' . $route->component . '/' . $route->area . '/HTML/Asset/' . $this->type . '/';
          // src/Component/CMS/Media/Admin/HTML/Asset/CSS/Select.css
          //die($asset . '<pre>' . print_r($dirs, true));
        }

        if ($parts[0] == 'Core') {
          $dirs[] = FS_SITE_TEMPLATE . $route->template . '/' . $this->type . '/';
          $dirs[] = FS_CORE_TEMPLATE . $route->template . '/' . $this->type . '/';

          $dirs[] = FS_CORE_ASSET . $this->type . '/';
        }

        unset($parts[0]);

        $asset = implode('/', $parts);

        for ($i = 0; $i < count($dirs); $i++) {
          $file = $dirs[$i] . $asset . '.' . $this->extension;

          if ($i == 0) {
            $file = strtolower($file);
          }

          if (file_exists($file)) {
            $app->debug->add('Add Asset ' . $this->type, "Adding - $asset - " . $this->absToRel($file));
            $this->asset[$asset] = $file;
            $this->addFileInline($file);

            break;
          } else {
            $app->debug->add('Add Asset ' . $this->type, "Not Found - $asset - " . $this->absToRel($file));
          }
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
    $absolute = (strpos($file, FS_SITE) !== false);
    $local    = (substr($file, 0, 4) != 'http' && substr($file, 0, 2) != '//');

    Application::getInstance()->debug->add('Add ' . $this->type . ' Asset File', "Seach for " . $file);

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
      Application::getInstance()->debug->add('Add ' . $this->type . ' Asset File', " Found " . $file);
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

  public function addFileInline(string $file)
  {
    if (strpos($file, FS_SITE) !== false) {
      if (file_exists($file)) {
        $this->addInline(file_get_contents($file));
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

    foreach ($this->files->local as $asset) {
      $file = '/' . $this->absToRel($asset->file);

      // Add local asset
      $html .= $this->getFileTag($file, $asset->args);
    }

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
