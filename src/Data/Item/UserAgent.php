<?php

namespace Adept\Data\Item;

use Adept\Application\Database;
use WhichBrowser\Parser;

defined('_ADEPT_INIT') or die();

class Useragent extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Useragent';
  protected string $index = 'useragent';

  public string $useragent = '';
  public string $friendly  = '';
  public string $browser = '';
  public string $os = '';
  public string $device = '';
  public string $type = '';
  public bool   $isDetected = false;
  public string $status = 'Allow';

  public function __construct(bool $current = false)
  {
    parent::__construct();

    if ($current) {
      $useragent = $_SERVER['HTTP_USER_AGENT'];

      // TODO: Create a seperate function for creating a new record from a useragent string
      if (!$this->loadFromIndex($useragent)) {
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

        $this->isDetected = $parser->isDetected();

        $this->save();
      }
    }
  }

  public function loadFromIndex(string|int $val): bool
  {
    $val = trim($val);
    $val = filter_var($val, FILTER_UNSAFE_RAW);

    return parent::loadFromIndex($val);
  }
}
