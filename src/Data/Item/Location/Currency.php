<?php

namespace Adept\Data\Item\Location;

use DateTime;

defined('_ADEPT_INIT') or die('No Access');

class Currency extends \Adept\Abstract\Data\Item
{
  protected string $table = 'LocationCurrency';
  protected string $index = 'currency';

  public string    $currency;
  public string    $code;
  public string    $symbol;
  public string    $subunit;
  public int       $subunitRatio;
  public string    $centralBank;
}
