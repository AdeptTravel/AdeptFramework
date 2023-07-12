<?php

namespace AdeptCMS\Base;

abstract class Configuration
{
  public $site;
  public $admin;
  public $user;
  public $debug;
  public $company;
  public $breadcrumbs;
  public $defaults;
  public $database;
  public $share;
  public $optimize;
  public $security;
  public $template;

  abstract public function __construct();
}
