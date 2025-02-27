<?php

namespace Adept\Application;

use Adept\Application;

class Configuration
{

  protected array $config = [];

  public function __construct(array $default)
  {
    $this->config = $default;
  }

  public function load()
  {
    $app   = Application::getInstance();
    $route = $app->session->request->route;
    $url   = $app->session->request->url;

    // Component Conf
    if (
      file_exists($file = FS_CORE_COMPONENT . "$route->type/$route->component/$route->area/$url->type/$route->view.Conf.php") &&
      is_array($config = include $file)
    ) {
      $this->config = array_merge($this->config, $config);
    }

    // Module Conf
    // Event Conf
    // Database Override

    $this->config = array_merge($this->config, $route->params);
  }

  public function getString(string $key, string $default = ''): string
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = $default;

    if (
      substr($key, 0, 9) == 'component' &&
      array_key_exists('Component.WhiteList.Get', $this->config) &&
      $get->exists($key)
    ) {
      $data = $get->getString($key);
    } else if (array_key_exists($key, $this->config)) {
      $data = (string)$this->config[$key];
    }

    return $data;
  }

  public function getInt(string $key, int $default = 0): int
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = $default;

    if (
      substr($key, 0, 9) == 'component' &&
      array_key_exists('Component.WhiteList.Get', $this->config) &&
      $get->exists($key)
    ) {
      $data = $get->getInt($key);
    } else if (array_key_exists($key, $this->config)) {
      $data = (int)$this->config[$key];
    }

    return $data;
  }

  public function getBool(string $key, bool $default = false): bool
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = $default;

    if (
      substr($key, 0, 9) == 'component' &&
      array_key_exists('Component.WhiteList.Get', $this->config) &&
      $get->exists($key)
    ) {
      $data = $get->getBool($key);
    } else if (array_key_exists($key, $this->config)) {
      $data = (bool)$this->config[$key];
    }

    return $data;
  }

  public function getArray(string $key, array $default = []): array
  {
    //$get  = Application::getInstance()->session->request->data->get;
    $data = $default;

    if (array_key_exists($key, $this->config)) {
      $data = (array)$this->config[$key];
    }

    return $data;
  }
}
