<?php

namespace Adept\Document\HTML\Body;

defined('_ADEPT_INIT') or die('No Access');

class Modules
{
  protected $app;
  protected $doc;

  protected $modules;

  public function __construct(\Adept\Application &$app, \Adept\Document\HTML &$doc)
  {
    $this->app = $app;
    $this->doc = $doc;
    $this->modules = [];
  }

  public function getModule(string $name, array $args): \Adept\Abstract\Module|false
  {
    $module = false;

    $area =  $this->app->session->request->route->area;
    $path = FS_MODULE . $area . '/';
    $aname = $this->match($path, $name);

    if (!empty($aname)) {
      $namespace = "\\Module\\" . $area . "\\" . $aname;
      $module = new $namespace($this->app, $this->doc);

      if (!empty($args)) {
        $module->load($args);
      }
    }

    return $module;
  }

  /**
   * Match a string to it's case sensitive directory/file within a given path
   *
   * @param string $path
   * @param string $search
   * 
   * @return string Case sensitive match, empty if not found
   */
  public function match(string $path, string $search): string
  {
    $result = '';

    foreach (scandir($path) as $fs) {
      if ($fs == '.' || $fs == '..' || is_dir($fs)) {
        continue;
      }

      if ((str_replace('.php', '', strtolower($fs))) == strtolower($search)) {
        $result = str_replace('.php', '', $fs);
        break;
      }
    }

    return $result;
  }
}
