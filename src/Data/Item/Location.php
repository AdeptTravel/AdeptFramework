<?php

namespace Adept\Data\Item\Location;

use DateTime;

defined('_ADEPT_INIT') or die('No Access');

class Country extends \Adept\Abstract\Data\Item
{
  protected string $table = 'LocationCountry';
  protected string $index = 'country';

  public int       $contentId;
  public int       $currencyId;

  public string    $country;
  public string    $iso2;
  public string    $iso3;
  public string    $phoneCode;
  public string    $region;
  public string    $subregion;
}
