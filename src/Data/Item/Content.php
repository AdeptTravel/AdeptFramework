<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Helper\Strings;

class Content extends \Adept\Abstract\Data\Item
{

  protected string $table = 'Content';
  protected string $index = 'route';

  protected array  $excludeKeys = ['path'];

  protected array  $postFilters = [
    'summary' => 'html',
    'content' => 'html',
  ];

  public int       $parentId;
  public int       $routeId;
  public int       $imageId;
  public int       $userId;
  //ENUM('Article', 'Category', 'Component', 'Tag')
  public string    $type;
  //ENUM('', 'Blog', 'News', 'Video') DEFAULT '',
  public string    $subtype;
  public string    $title;
  public string    $summary;
  public string    $content;
  public string    $metaTitle;
  public string    $metaDescription;
  public string    $ogTitle;
  public string    $ogDescription;
  public string    $ogLocale;
  public int       $ogImageId;
  public string    $xTitle;
  public string    $xDescription;
  public int       $xImageId;
  //ENUM('summary', 'summary_large_image', 'app', 'player')
  public string    $xCardType = 'summary_large_image';
  public object    $params;
  public \DateTime $activeOn;
  public \DateTime $archiveOn;
  public int       $sortOrder;
  //`status`          ENUM('Active', 'Archive', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  public string    $status = 'Active';

  public function getParent(): \Adept\Data\Item\Content
  {
    $parent = new \Adept\Data\Item\Content();

    if (!empty($this->parentId)) {
      $parent->loadFromId($this->parentId);
    }

    return $parent;
  }

  public function getRoute(): \Adept\Data\Item\Route
  {
    $route = new \Adept\Data\Item\Route();

    if (isset($this->routeId)) {
      $route->loadFromId($this->routeId);
    }

    return $route;
  }

  public function getImage(): \Adept\Data\Item\Media\Image
  {
    $image = new \Adept\Data\Item\Media\Image();
    $image->loadFromId($this->imageId);
    return $image;
  }

  public function getUser(): \Adept\Data\Item\User
  {
    $user = new \Adept\Data\Item\User();
    $user->loadFromId($this->userId);
    return $user;
  }

  protected function cleanSortOrder()
  {
    // Update the sort orders to be sequential
    $db = Application::getInstance()->db;

    $query  = "UPDATE Content";
    $query .= " JOIN (";
    $query .= " SELECT ";
    $query .= " Content.* ";
    $query .= " , @n := @n + 1 as n";
    $query .= " FROM Content ";
    $query .= " , (SELECT @n := 0) var_init";
    $query .= " ORDER BY sortOrder ";
    $query .= " ) a ON Content.id = a.id";
    $query .= " SET Content.sortOrder = a.n;";

    $db->update($query, []);
  }

  protected function getNextSortOrder(): int
  {
    $this->cleanSortOrder();

    // Get the last sort order in the parent
    $db = Application::getInstance()->db;
    $query = "SELECT sortOrder FROM Content";
    $params = [];

    if (!empty($this->parentId)) {
      $query .= " WHERE parent = ?";
      $params[] = $this->parentId;
    }
    $query .= " ORDER BY sortOrder DESC LIMIT 1";

    $order = $db->getValue($query, $params);
    return (!empty($order)) ? $order + 1 : 1;
  }

  public function generateRoute(): string
  {
    $route = $this->getRoute();

    if (!empty($this->title)) {
      if (!empty($this->parentId)) {
        $parent = $this->getParent();
        $parentRoute = $parent->getRoute()->route . '/' . $route;
      }

      $db = Application::getInstance()->db;

      $routes = $db->getValues(
        "SELECT `Route`.`route` FROM `Content` INNER JOIN `Route` ON `Content`.`routeId` = `Route`.`id` WHERE `Route`.`route` LIKE ? ORDER BY `Route`.`route` ASC;",
        [$route . '%']
      );

      if (in_array($route, $routes)) {
        $used = [];

        // Parse the array to find used increments
        for ($i = 0; $i < count($routes); $i++) {
          if (strpos($path, $basePath . '-') === 0) {
            $suffix = substr($routes[$i], strlen($route) + 1);

            if (ctype_digit($suffix)) {
              $used[] = (int)$suffix;
            }
          }
        }

        // Sort increments and find the first missing one
        sort($used);
        $next = 1;

        for ($i = 0; $i < count($used); $i++) {
          if ($used[$i] === $next) {
            $next++;
          } else {
            break;
          }
        }
      }

      /*
      while (in_array($route, $routes)) {
        // Check if the current URL already ends with a dash and a number
        if (preg_match('/-(\d+)$/', $route, $matches)) {
          // If it does, increment the number
          $next = (int)$matches[1] + 1;
          // Remove the existing increment and replace it with the new one
          $new = preg_replace('/-(\d+)$/', '-' . $next, $route);
        } else {
          // If no increment exists, append the first increment
          $route = $route . '-' . $next;
        }
        
      }*/
    } else {
      // TODO: Throw validation error - Missing required field
    }

    return $route;
  }


  public function save(): bool
  {
    $route = $this->getRoute();
    $post  = Application::getInstance()->session->request->data->post;

    if (!empty($postRoute = $post->getString('route'))) {
      // Alias isn't empty,
      if ($route->route != $postRoute) {
        if (strpos($postRoute, '/') !== false) {
          $parts = explode('/', $postRoute);

          for ($i = 0; $i < count($parts); $i++) {
            $parts[0] = $route->formatSegment($parts[$i]);
          }

          $postRoute = implode('/', $parts);

          if (!$route->routeExists($postRoute)) {
            $route->route = $postRoute;
          }
        } else if (!empty($newRoute = $post->getString('route'))) {
          $route->route = $route->formatSegment($newRoute);
        }
      }
    } else {
      if (!empty($this->parentId)) {
        $parentRoute = $this->getParent()->getRoute()->route;
        $route->route = $parentRoute . '/' . $route->formatSegment($this->title);
      } else {
        $route->route = $route->formatSegment($this->title);
      }
    }

    $route->save();

    $this->routeId = $route->id;

    if (empty($this->metaTitle)) {
      $this->metaTitle = $this->title;
    }

    if (empty($this->ogTitle)) {
      $this->ogTitle = $this->title;
    }

    if (empty($this->xTitle)) {
      $this->xTitle = $this->title;
    }

    if (!empty($this->summary)) {
      $desc = Strings::truncate(strip_tags($this->summary), 300);

      if (empty($this->metaDescription)) {
        $this->metaDescription = $desc;
      }

      if (empty($this->ogDescription)) {
        $this->ogDescription = strip_tags(Strings::truncate($this->summary, 300));
      }

      if (empty($this->xDescription)) {
        $this->xDescription = strip_tags(Strings::truncate($this->summary, 300));
      }
    }

    if (!empty($this->imageId)) {
      if (empty($this->ogImageId)) {
        $this->ogImageId = $this->imageId;
      }

      if (empty($this->xImageId)) {
        $this->xImageId = $this->imageId;
      }
    }

    // TODO: Check for conflicts and reorder.
    if (empty($sortOrder)) {
      $db = Application::getInstance()->db;
      $sortOrder = $db->getValue(
        "SELECT sortOrder FROM Content WHERE parentId = ? ORDER BY sortOrder DESC LIMIT 1",
        [(!empty($this->parentId)) ? $this->parentId : 0]
      );

      $this->sortOrder = (!empty($sortOrder)) ? $sortOrder : 0;
    }

    // Do somthing with sortOrder here

    //die('<pre>' . print_r($this, true));
    return parent::save();
  }
}
