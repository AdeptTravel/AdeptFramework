<?php

namespace Adept\Abstract;

use \Adept\Abstract\Configuration\App;
use \Adept\Abstract\Configuration\Assets;
use \Adept\Abstract\Configuration\Breadcrumbs;
use \Adept\Abstract\Configuration\Company;
use \Adept\Abstract\Configuration\Component;
use \Adept\Abstract\Configuration\Database;
use \Adept\Abstract\Configuration\Email;
use \Adept\Abstract\Configuration\Media;
use \Adept\Abstract\Configuration\Optimize;
use \Adept\Abstract\Configuration\Security;
use \Adept\Abstract\Configuration\Site;
use \Adept\Abstract\Configuration\System;

abstract class Configuration
{
  public App $app;
  public Assets $assets;
  public Breadcrumbs $breadcrumbs;
  public Company $company;
  public Component $component;
  public Database $database;
  public Email $email;
  public Media $media;
  public Security $security;
  public Site $site;
  public System $system;

  public function __construct()
  {
    $this->app         = new App();
    $this->assets      = new Assets();
    $this->breadcrumbs = new Breadcrumbs();
    $this->company     = new Company();
    $this->component   = new Component();
    $this->database    = new Database();
    $this->email       = new Email();
    $this->media       = new Media();
    $this->security    = new Security();
    $this->site        = new Site();
    $this->system      = new System();
  }
}
