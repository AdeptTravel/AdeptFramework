<?php

namespace Adept\Data\Table\Location;

defined('_ADEPT_INIT') or die();

class Currency extends \Adept\Abstract\Data\Table
{
  protected string $table = 'LocationCurrency';
  protected array  $like = ['currency'];

  public string    $sort = 'currency';

  public string    $currency;
  public string    $code;
  public string    $symbol;
  public string    $subunit;
  public int       $subunitRatio;
  public string    $centralBank;


  public function getItem(int $id = 0): \Adept\Data\Item\Location\Currency
  {
    $item = new \Adept\Data\Item\Location\Currency();
    $item->loadFromId($id);
    return $item;
  }
}
