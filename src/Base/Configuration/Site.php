<?php

namespace AdeptCMS\Base\Site;

abstract class Site
{
  public $site;
  public $admin;
  public $user;
  public $company;
  public $breadcrumbs;
  public $defaults;
  public $database;
  public $share;
  public $optimize;
  public $security;

  abstract public function __construct();
}
