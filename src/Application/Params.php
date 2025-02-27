<?php

namespace Adept\Application;

use Adept\Application;

class Params
{
  protected object $data;

  protected function getConfigValue($conf, $path)
  {
    $keys = explode('->', $path); // Convert string path to array
    return array_reduce($keys, function ($carry, $key) {
      return isset($carry->$key) ? $carry->$key : null; // Traverse object properties
    }, $conf);
  }

  protected function replaceConfigReferences($jsonString, $conf)
  {
    // Match all occurrences of text containing "->" (assumes values are strings)
    return preg_replace_callback('/"([^"]*->[^"]*)"/', function ($matches) use ($conf) {
      $path = $matches[1]; // Extract matched path
      $value = $this->getConfigValue($conf, $path); // Fetch value from config
      return json_encode($value); // Ensure proper JSON encoding
    }, $jsonString);
  }

  protected function get(string $key, string $default = ''): string
  {
    $app   = Application::getInstance();
    $get   = $app->session->request->data->get;
    $route = $app->session->request->route;

    $data = $default;

    if ($route->allowGet && $get->exists($key)) {
      $data = $get->getString($key);
    } else if (!empty($this->data->$key)) {
      $data = $this->data->$key;
    }

    return $data;
  }

  public function __construct()
  {
    $this->data = new \stdClass();

    //namespace Adept\Component\System\Route\Admin\HTML;
    $app   = Application::getInstance();
    $url   = $app->session->request->url;
    $route = $app->session->request->route;

    //$app->conf->data->table->limit;
    $path = FS_CORE_COMPONENT . "$route->type/$route->component/$route->area/$url->type/$route->view.json";

    if (file_exists($path)) {
      $json = file_get_contents($path);

      // Loop through the text to see if there are any ->, if there are replace the whole 
      // value with the conf value.
      $json = $this->replaceConfigReferences($json, $app->conf);

      $this->data = json_decode($json);
      // TODO: We need to look through the values to see if there is 

      foreach ($route->params as $k => $v) {
        $this->data->$k = $v;
      }
    }
  }

  public function getString(string $key, string $default = ''): string
  {
    return $this->get($key, $default);
    //return (!empty($this->data->$key)) ? (string)$this->data->$key : $default;
  }

  public function getInt(string $key, int $default = 0): int
  {
    return (int)$this->get($key, $default);
    //return (!empty($this->data->$key)) ? (int)$this->data->$key : $default;
  }

  public function getBool(string $key, bool $default = false): bool
  {
    return (bool)$this->get($key, $default);
    return (!empty($this->data->$key)) ? (bool)$this->data->$key : $default;
  }
}
