<?php

namespace Adept\Document\HTML;

defined('_ADEPT_INIT') or die();

use \Adept\Application;
use \Adept\Data\Table\Module as Table;
use \Adept\Data\Item\Module as Item;

class Module
{

  protected array $data;

  public function __construct()
  {
    $table = new Table();
    $this->data  = $table->getData();
  }

  public function getBuffer(string $type, string $name, object|null $params = null): string
  {
    $buffer = '';

    if ($type == 'area') {
      $template = Application::getInstance()->session->request->route->template;

      for ($i = 0; $i < count($this->data); $i++) {
        if ($this->data[$i]->template == $template && $this->data[$i]->area == $name) {
          $params = json_decode($this->data[$i]->params);

          if ($module = $this->getModule($this->data[$i]->module, $params)) {
            $buffer .= $module->getBuffer();
          }
        }
      }
    } else if ($type == 'module') {
      if ($module = $this->getModule($name, $params)) {
        $buffer = $module->getBuffer();
      }
    }

    return $buffer;
  }

  public function getModule(string $path, object|null $params): \Adept\Abstract\Document\HTML\Module|null
  {
    $module = null;
    $namespace = '';
    $parts = explode('/', $path);

    $path .= '/' . end($parts);

    if (file_exists(FS_SITE_MODULE . $path . '.php')) {
      $namespace = "\\Module\\" . str_replace('/', '\\', $path);
    } else if (file_exists(FS_CORE_MODULE . $path . '.php')) {
      $namespace = "\\Adept\\Module\\" . str_replace('/', '\\', $path);
    } else {
      die(__FILE__ . '(' . __LINE__ . ') - Error, no module found at ' . $path);
    }

    if (class_exists($namespace)) {
      $module = new $namespace($params);
    } else {
      // TODO: Add more graceful error checking here
      die(__FILE__ . '(' . __LINE__ . ') - Error, no module found for ' . $namespace);
    }

    return $module;
  }
}
