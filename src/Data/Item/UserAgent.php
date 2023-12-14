<?php

namespace Adept\Data\Item;

use \Adept\Application\Database;
use WhichBrowser\Model\Version;

defined('_ADEPT_INIT') or die();

class UserAgent extends \Adept\Abstract\Data\Item
{
  protected string $name = 'User Agent';

  public string $raw = '';
  public string $useragent = '';
  public BOOL $detected = false;
  public string $browser = '';

  public string $browser_using_name = '';
  public string $browser_using_version = '';
  public string $browser_type = '';
  public string $browser_name = '';
  public string $browser_version = '';
  public string $browser_family_name = '';
  public string $browser_family_version = '';
  public string $engine = '';
  public string $engine_name = '';
  public string $engine_version = '';
  public string $os = '';
  public string $os_family = '';
  public string $os_name = '';
  public string $os_version = '';
  public string $device = '';
  public string $device_manufacturer = '';
  public string $device_model = '';
  public string $device_identifier = '';
  public string $device_type = '';
  public string $device_subtype = '';
  public bool $block = false;
  public \DateTime $created;

  public function __construct(Database &$db, int|string $id = 0)
  {
    $useragent = '';

    if ($id == 0) {
      $useragent = $_SERVER['HTTP_USER_AGENT'];
      $useragent = trim($useragent);
      $useragent = filter_var($useragent, FILTER_UNSAFE_RAW);

      if (empty($useragent) || strlen($useragent) >= 512) {
        die();
      }
    }

    parent::__construct($db, (((is_numeric($id) && $id > 0) || !empty($id)) ? $id : $useragent));

    //if (empty($this->raw)) {
    if ($this->id == 0) {
      $parser = new \WhichBrowser\Parser($useragent);

      $this->raw = $useragent;
      $this->useragent = $parser->toString();
      $this->useragent = $useragent;

      if (!empty($parser->isDetected())) $this->detected = $parser->isDetected();
      if (!empty($parser->toString())) $this->browser = $parser->toString();
      if (isset($parser->browser->using) && !empty($parser->browser->using->name)) $this->browser_using_name = $parser->browser->using->name;
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->toString())) $this->browser_using_version = $parser->browser->using->version->toString();

      //if (!empty($parser->browser->mode)) $this->browser_mode = $parser->browser->mode;
      if (!empty($parser->browser->type)) $this->browser_type = $parser->browser->type;
      if (!empty($parser->browser->getName())) $this->browser_name = $parser->browser->getName();
      if (isset($parser->browser->version) && !empty($parser->browser->version->toString())) $this->browser_version = $parser->browser->version->toString();
      if (isset($parser->browser->family) && !empty($parser->browser->family->name)) $this->browser_family_name = $parser->browser->family->name;
      if (isset($parser->browser->family) && !empty($parser->browser->family->version)) $this->browser_family_version = $parser->browser->family->version;
      if (!empty($parser->engine->toString())) $this->engine = $parser->engine->toString();
      if (!empty($parser->engine->name)) $this->engine_name = $parser->engine->name;
      if (isset($parser->engine->version) && !empty($parser->engine->version->toString())) $this->engine_version = $parser->engine->version->toString();
      if (!empty($parser->os->toString())) $this->os = $parser->os->toString();
      if (isset($parser->os->family) && !empty($parser->os->family->toString())) $this->os_family = $parser->os->family->toString();
      if (!empty($parser->device->toString())) $this->device = $parser->device->toString();
      if (!empty($parser->device->manufacturer)) $this->device_manufacturer = $parser->device->manufacturer;
      if (!empty($parser->device->model)) $this->device_model = $parser->device->model;
      if (!empty($parser->device->identifier)) $this->device_identifier = $parser->device->identifier;
      if (!empty($parser->device->type)) $this->device_type = $parser->device->type;
      if (!empty($parser->device->subtype)) $this->device_subtype = $parser->device->subtype;


      $this->save();
    }
  }
}
