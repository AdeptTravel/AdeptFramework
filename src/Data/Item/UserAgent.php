<?php

namespace Adept\Data\Item;

use Adept\Application\Database;
use WhichBrowser\Parser;

defined('_ADEPT_INIT') or die();

class Useragent extends \Adept\Abstract\Data\Item
{

  public string $useragent = '';
  public string $friendly  = '';
  public string $browser = '';
  public string $os = '';
  public string $device = '';
  public string $type = '';
  public bool   $detected = false;
  public bool   $block = false;

  public string $created;

  public function __construct(int|string|object $val = 0, bool $cache = true)
  {
    $useragent = '';

    if ($val === 0) {
      $useragent = $_SERVER['HTTP_USER_AGENT'];
      $useragent = trim($useragent);
      $useragent = filter_var($useragent, FILTER_UNSAFE_RAW);

      if (empty($useragent) || strlen($useragent) >= 512) {
        die();
      }
    }

    parent::__construct((((is_numeric($val) && $val > 0) || !empty($val)) ? $val : $useragent));

    if ($this->id == 0) {
      $parser = new Parser($useragent);
      $this->useragent = $useragent;

      if (!empty($parser->toString())) {
        $this->friendly = $parser->toString();
      }

      if (!empty($parser->browser->name)) {
        $this->browser = $parser->browser->name;
      }

      if (!empty($parser->os->alias)) {
        $this->os = $parser->os->alias;
      } else if (!empty($parser->os->name)) {
        $this->os = $parser->os->name;
      }

      if (!empty($parser->device->model)) {
        $this->device = $parser->device->model;
      }

      if (!empty($parser->device->type)) {
        $this->type = $parser->device->type;
      }

      $this->detected = $parser->isDetected();

      $this->save();
    }
  }
}
