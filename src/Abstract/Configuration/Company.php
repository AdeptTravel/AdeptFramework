<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Company\Address;
use \Adept\Abstract\Configuration\Company\Phone;

class Company
{
  public string $name;

  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Company\Address
   */
  public Address $address;

  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Company\Phone
   */
  public Phone $phone;

  public function __construct()
  {
    $this->address = new Address();
    $this->phone = new Phone();
  }
}
