<?php

namespace Adept\Data\Item\Location;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;
use \Adept\Application\Session\Request\Data\Post;

class Country extends \Adept\Abstract\Data\Item
{

  protected string $name = 'Country';
  protected string $table = 'location_country';

  public string $country = '';
  public string $iso2 = '';
  public string $iso3 = '';
  public string $currency = '';
  public string $currency_code = '';
  public string $phone_code = '';
  public string $region = '';
  public string $subregion = '';

  public function loadFromPost(Post $post, string $prefix = '')
  {
    parent::loadFromPost($post, $prefix);

    if (strlen($this->country) == 2) {
      $this->load($this->country, 'iso2');
    } else if (!empty($this->iso2)) {
      $this->load($this->iso2, 'iso2');
    } else if (!empty($this->iso3)) {
      $this->load($this->iso3, 'iso3');
    } else if (!empty($this->country)) {
      $this->load($this->country, 'iso2');
    }
  }
  /*
  public function save(): bool
  {
    return true;
  }
  */
}
