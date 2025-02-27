<?php

namespace Adept\Application;

defined('_ADEPT_INIT') or die();

class Debug
{
  protected array $data;

  public function add(string $type, string $message)
  {
    if (\Adept\Application::getInstance()->conf->system->debug) {
      $this->data[] = $type . ' - ' . $message;
    }
  }

  public function getBuffer(): string
  {
    $buffer = '';

    if (\Adept\Application::getInstance()->conf->system->debug) {
      $buffer .= "<!-- Debug Data\n\n";
      for ($i = 0; $i < count($this->data); $i++) {
        $buffer .= $this->data[$i] . "\n";
      }
      $buffer .= "\n\n-->\n\n";
    }

    return $buffer;
  }
}
