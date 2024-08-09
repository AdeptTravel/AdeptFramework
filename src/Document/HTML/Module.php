<?php

namespace Adept\Document\HTML;

defined('_ADEPT_INIT') or die();

use \Adept\Application;
use \Adept\Document\HTML\Head;

class Module
{
  protected array $data;

  public function getModule(string $tag): string
  {
    // Tag format  - module:Path/To/Module:args=as&key=val
    $buffer = '';

    $parts = explode(':', $tag);

    if ($parts >= 2) {
      $args = [];

      if (count($parts) == 3) {
        parse_str($parts[2], $args);
      }

      if (($file = $this->getFile($parts[1])) !== false) {
        ob_start();
        include($file);
        $buffer = ob_get_contents();
        ob_end_clean();
      }
    }

    return $buffer;
  }

  protected function getFile(string $path): string|bool
  {
    $paths = [FS_SITE_MODULE, FS_CORE_MODULE];
    $file = false;

    for ($i = 0; $i < count($paths); $i++) {
      if (file_exists($file = $paths[$i] . $path . '/' . '/Module.php')) {
        break;
      }
    }

    return $file;
  }
}
