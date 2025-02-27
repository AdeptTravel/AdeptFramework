<?php

namespace Adept\Data\Table\Location;

defined('_ADEPT_INIT') or die();

class Country extends \Adept\Abstract\Data\Table
{
  protected string $table = 'LocationCountry';
  protected array  $like = ['country'];

  public string    $sort = 'currency';

  public int       $contentId;
  public int       $currencyId;

  public string    $country;
  public string    $iso2;
  public string    $iso3;

  public string    $phoneCode;
  public string    $region;
  public string    $subregion;

  public function getItem(int $id = 0): \Adept\Data\Item\Location\Country
  {
    $item = new \Adept\Data\Item\Location\Country();
    $item->loadFromId($id);
    return $item;
  }
}
