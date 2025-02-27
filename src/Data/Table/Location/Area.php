<?php

namespace Adept\Data\Table\Location;

defined('_ADEPT_INIT') or die();

class Area extends \Adept\Abstract\Data\Table
{
  protected string $table = 'LocationArea';
  protected array  $joinInner = ['LocationCountry' => 'countryId'];
  public int       $countryId;

  public string $city;
  public string $county;
  public string $state;
  public string $postalcode;
  public string $timezone;

  public float  $latitude;
  public float  $longitude;
  public float  $altitude;

  public function getItem(int $id = 0): \Adept\Data\Item\Location\Area
  {
    $item = new \Adept\Data\Item\Location\Area();
    $item->loadFromId($id);
    return $item;
  }
}
