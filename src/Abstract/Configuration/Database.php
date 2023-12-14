<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

class Database
{
  public string $type = 'mysql ';
  public string $host = 'localhost';
  public string $port = '';
  public string $username;
  public string $password;
  public string $database;
  public string $cert = '';
  public string $key = '';
}
