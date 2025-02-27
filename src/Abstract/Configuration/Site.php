<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Site\Legal;

class Site
{
  public string $name;
  public Legal  $legal;
  public array  $host;
  // `area` ENUM('Admin', 'Global', 'Public') NOT NULL,
  public string $area;
  // `type` ENUM('BI', 'CMS', 'CRM', 'Core', 'ERP', 'Shop', 'System') NOT NULL, 
  public array $type;
  public string $template = 'Default';
  public string $images;

  public function __construct()
  {
    $this->legal = new Legal();
  }
}
