<?php

namespace Adept\Data\Item\Location;

use DateTime;

defined('_ADEPT_INIT') or die('No Access');

class Area extends \Adept\Abstract\Data\Item
{
  protected string $table = 'LocationArea';

  public int    $countryId;

  public string $city;
  public string $county;
  public string $state;
  public string $postalcode;
  public string $timezone;

  public float  $latitude;
  public float  $longitude;
  public float  $altitude;
}
