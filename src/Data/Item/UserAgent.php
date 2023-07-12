<?php

namespace AdeptCMS\Data\Item;

use WhichBrowser\Model\Version;

defined('_ADEPT_INIT') or die();

class UserAgent extends \AdeptCMS\Base\Data\Item
{

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $raw = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $useragent = '';

  /**
   * Undocumented variable
   *
   * @var boolean
   */
  public $detected = false;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using_name = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using_version = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using_version_value = '';

  /**
   * Undocumented variable
   *
   * @var boolean
   */
  public $browser_using_version_hidden = false;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using_version_nickname = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using_version_alias = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using_version_details = 0;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_using_version_builds = false;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_channel = '';

  /**
   * Undocumented variable
   *
   * @var boolean
   */
  public $browser_stock = false;

  /**
   * Undocumented variable
   *
   * @var boolean
   */
  public $browser_hidden = false;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_mode = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_type = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_name = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_alias = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $browser_version = '';


  public $browser_version_value = '';
  public $browser_version_hidden = false;
  public $browser_version_nickname = '';
  public $browser_version_alias = '';
  public $browser_version_details = 0;
  public $browser_version_builds = false;
  public $browser_family = '';
  public $browser_family_name = '';
  public $browser_family_version = '';
  public $engine = '';
  public $engine_name = '';
  public $engine_alias = '';
  public $engine_version = '';
  public $engine_version_value = '';
  public $engine_version_hidden = false;
  public $engine_version_nickname = '';
  public $engine_version_alias = '';
  public $engine_version_details = 0;
  public $engine_version_builds = false;
  public $os = '';
  public $os_family = '';
  public $os_edition = '';
  public $os_hidden = false;
  public $os_name = '';
  public $os_alias = '';
  public $os_version = '';
  public $os_version_value = '';
  public $os_version_hidden = false;
  public $os_version_nickname = '';
  public $os_version_alias = '';
  public $os_version_details = 0;
  public $os_version_builds = false;
  public $device = '';
  public $device_manufacturer = '';
  public $device_model = '';
  public $device_series = '';
  public $device_carrier = '';
  public $device_identifier = '';
  public $device_flag = '';
  public $device_type = '';
  public $device_subtype = '';
  public $device_identified = 0;
  public $device_generic = false;
  public $device_hidden = false;
  public $camouflage = false;
  public $features = '';
  public $block = false;
  public $created = '0000-00-00 00:00:00';

  public function __construct(\AdeptCMS\Application\Database &$db, int|string $id = 0)
  {

    $useragent = null;

    if ($id == 0) {
      $useragent = $_SERVER['HTTP_USER_AGENT'];
      $useragent = trim($useragent);
      $useragent = filter_var($useragent, FILTER_UNSAFE_RAW);

      if (
        empty($useragent)
        || is_numeric($useragent)
        || strlen($useragent) >= 512
      ) {
        die();
      }
    }

    parent::__construct($db, ($id > 0) ? $id : $useragent);

    if (empty($this->raw)) {
      $parser = new \WhichBrowser\Parser($useragent);

      $this->raw = $useragent;
      $this->useragent = $parser->toString();

      if (!empty($parser->isDetected())) $this->detected = $parser->isDetected();
      if (!empty($parser->toString())) $this->browser = $parser->toString();
      if (isset($parser->browser->using) && !empty($parser->browser->using->toString())) $this->browser_using = $parser->browser->using->toString();
      if (isset($parser->browser->using) && !empty($parser->browser->using->name)) $this->browser_using_name = $parser->browser->using->name;
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->toString())) $this->browser_using_version = $parser->browser->using->version->toString();
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->value)) $this->browser_using_version_value = $parser->browser->using->version->value;
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->hidden)) $this->browser_using_version_hidden = $parser->browser->using->version->hidden;
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->nickname)) $this->browser_using_version_nickname = $parser->browser->using->version->nickname;
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->alias)) $this->browser_using_version_alias = $parser->browser->using->version->alias;
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->details)) $this->browser_using_version_details = $parser->browser->using->version->details;
      if (isset($parser->browser->using) && !empty($parser->browser->using->version->builds)) $this->browser_using_version_builds = $parser->browser->using->version->builds;
      if (!empty($parser->browswer->channel)) $this->browser_channel = $parser->browswer->channel; // Maybe not available
      if (!empty($parser->browser->stock)) $this->browser_stock = $parser->browser->stock;
      if (!empty($parser->browser->hidden)) $this->browser_hidden = $parser->browser->hidden;
      if (!empty($parser->browser->mode)) $this->browser_mode = $parser->browser->mode;
      if (!empty($parser->browser->type)) $this->browser_type = $parser->browser->type;
      if (!empty($parser->browser->getName())) $this->browser_name = $parser->browser->getName();
      if (!empty($parser->browser->alias)) $this->browser_alias = $parser->browser->alias;
      if (!empty($parser->browser->version->toString())) $this->browser_version = $parser->browser->version->toString();
      if (!empty($parser->browser->version->value)) $this->browser_version_value = $parser->browser->version->value;
      if (!empty($parser->browser->version->hidden)) $this->browser_version_hidden = $parser->browser->version->hidden;
      if (!empty($parser->browser->version->nickname)) $this->browser_version_nickname = $parser->browser->version->nickname;
      if (!empty($parser->browser->version->alias)) $this->browser_version_alias = $parser->browser->version->alias;
      if (!empty($parser->browser->version->details)) $this->browser_version_details = $parser->browser->version->details;
      if (!empty($parser->browser->version->builds)) $this->browser_version_builds = $parser->browser->version->builds;
      if (isset($parser->browser->family) && !empty($parser->browser->family->toString())) $this->browser_family = $parser->browser->family->toString();
      if (isset($parser->browser->family) && !empty($parser->browser->family->name)) $this->browser_family_name = $parser->browser->family->name;
      if (isset($parser->browser->family) && !empty($parser->browser->family->version)) $this->browser_family_version = $parser->browser->family->version;
      if (!empty($parser->engine->toString())) $this->engine = $parser->engine->toString();
      if (!empty($parser->engine->name)) $this->engine_name = $parser->engine->name;
      if (!empty($parser->engine->alias)) $this->engine_alias = $parser->engine->alias;
      if (isset($parser->engine->version) && !empty($parser->engine->version->toString())) $this->engine_version = $parser->engine->version->toString();
      if (isset($parser->engine->version) && !empty($parser->engine->version->value)) $this->engine_version_value = $parser->engine->version->value;
      if (isset($parser->engine->version) && !empty($parser->engine->version->hidden)) $this->engine_version_hidden = $parser->engine->version->hidden;
      if (isset($parser->engine->version) && !empty($parser->engine->version->nickname)) $this->engine_version_nickname = $parser->engine->version->nickname;
      if (isset($parser->engine->version) && !empty($parser->engine->version->alias)) $this->engine_version_alias = $parser->engine->version->alias;
      if (isset($parser->engine->version) && !empty($parser->engine->version->details)) $this->engine_version_details = $parser->engine->version->details;
      if (isset($parser->engine->version) && !empty($parser->engine->version->builds)) $this->engine_version_builds = $parser->engine->version->builds;
      if (!empty($parser->os->toString())) $this->os = $parser->os->toString();
      if (isset($parser->os->family) && !empty($parser->os->family->toString())) $this->os_family = $parser->os->family->toString();
      if (!empty($parser->os->edition)) $this->os_edition = $parser->os->edition;
      if (!empty($parser->os->hidden)) $this->os_hidden = $parser->os->hidden;
      if (!empty($parser->os->name)) $this->os_name  = $parser->os->name;
      if (!empty($parser->os->alias)) $this->os_alias = $parser->os->alias;
      if (!empty($parser->os->version->toString())) $this->os_version = $parser->os->version->toString();
      if (!empty($parser->os->version->value)) $this->os_version_value = $parser->os->version->value;
      if (!empty($parser->os->version->hidden)) $this->os_version_hidden = $parser->os->version->hidden;
      if (!empty($parser->os->version->nickname)) $this->os_version_nickname = $parser->os->version->nickname;
      if (!empty($parser->os->version->alias)) $this->os_version_alias = $parser->os->version->alias;
      if (!empty($parser->os->version->details)) $this->os_version_details = $parser->os->version->details;
      if (!empty($parser->os->version->builds)) $this->os_version_builds = $parser->os->version->builds;
      if (!empty($parser->device->toString())) $this->device = $parser->device->toString();
      if (!empty($parser->device->manufacturer)) $this->device_manufacturer = $parser->device->manufacturer;
      if (!empty($parser->device->model)) $this->device_model = $parser->device->model;
      if (!empty($parser->device->series)) $this->device_series = $parser->device->series;
      if (!empty($parser->device->carrier)) $this->device_carrier = $parser->device->carrier;
      if (!empty($parser->device->identifier)) $this->device_identifier = $parser->device->identifier;
      if (!empty($parser->device->flag)) $this->device_flag = $parser->device->flag;
      if (!empty($parser->device->type)) $this->device_type = $parser->device->type;
      if (!empty($parser->device->subtype)) $this->device_subtype = $parser->device->subtype;
      if (!empty($parser->device->identified)) $this->device_identified = $parser->device->identified;
      if (!empty($parser->device->generic)) $this->device_generic = $parser->device->generic;
      if (!empty($parser->device->hidden)) $this->device_hidden = $parser->device->hidden;
      if (!empty($parser->camouflage)) $this->camouflage = $parser->camouflage;
      if (!empty($parser->features)) $this->features = $parser->features;

      $this->save();
    }
  }
}
