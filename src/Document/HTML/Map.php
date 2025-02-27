<?php

namespace Adept\Document\HTML;

defined('_ADEPT_INIT') or die();

class Map
{
  protected string $route = '';
  protected array  $titles = [];
  protected array  $routes = [];

  public function __construct(string $route)
  {
    $this->route = $route;

    // Split the string by '/'
    $parts = explode('/', $route);

    // Build the array progressively
    for ($i = 0; $i < count($parts); $i++) {
      $r = implode('/', array_slice($parts, 0, $i + 1));
      $this->routes[] = $r;
    }
  }

  public function getMenuActive(string $route): bool
  {
    return ($route == end($this->routes));
  }

  public function getMenuParent(string $route): bool
  {
    $status = false;

    for ($i = 0; $i < count($this->routes); $i++) {
      if ($route == $this->routes[$i]) {
        $status = true;
        break;
      }
    }

    return $status;
  }

  public function getRouteTitle(string $route): string
  {
    $title = '';

    if ($route == '') {
      $title = 'Home';
    }

    return $title;
  }

  public function getRouteTitleFromMenu() {}

  public function getRouteTitleFromContent() {}

  // TODO: Add caching here
}
