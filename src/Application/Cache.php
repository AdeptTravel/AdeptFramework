<?php

namespace Adept\Application;

use Adept\Application;
use Adept\Application\Configuration;

class Cache
{
  public bool $enabled;
  public string $type;
  public int $ttl;

  public function __construct(Configuration $conf)
  {
    $this->enabled = $conf->getBool('Cache');
    $this->type = $conf->getString('Cache.Type');
    $this->ttl = $conf->getInt('Cache.TTL');
  }

  public function set(string $key, $val, $ttl)
  {
    apcu_store($key, $val, $ttl);
  }

  public function get(string $key)
  {
    if (apcu_exists($key)) {
      $data = apcu_fetch($key);
    }

    return $data;
  }
}
