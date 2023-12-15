<?php

namespace Adept\Component\Location;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Application\Session\Authentication;
use \Adept\Data\Items\Location\Area;

class Lookup extends \Adept\Abstract\Component
{
  public function __construct(Application &$app, Document &$doc)
  {
    parent::__construct($app, $doc);

    $post = &$this->app->session->request->data->post;

    $filter = [];

    if (!empty($post->getInt('postalcode', 0))) {
      $filter[] = (object)[
        'col' => 'postalcode',
        'val' => $post->getInt('postalcode'),
        'comp' => '='
      ];
    }

    if (!empty($post->getName('city', ''))) {
      $filter[] = (object)[
        'col' => 'city',
        'val' => $post->getName('city'),
        'comp' => '='
      ];
    }

    if (!empty($post->getName('county', ''))) {
      $filter[] = (object)[
        'col' => 'county',
        'val' => $post->getName('county'),
        'comp' => '='
      ];
    }

    if (!empty($post->getName('state', ''))) {
      $filter[] = (object)[
        'col' => 'region',
        'val' => $post->getName('state'),
        'comp' => '='
      ];
    }

    if (!empty($post->getName('region', ''))) {
      $filter[] = (object)[
        'col' => 'region',
        'val' => $post->getName('region'),
        'comp' => '='
      ];
    }

    if (!empty($filter)) {
      $area = new Area($app->db, $filter);
      $area->load();

      $city = [];
      $county = [];
      $state = [];
      $postalcode = [];

      for ($i = 0; $i < count($area->items); $i++) {
        if (!in_array($area->items[$i]->city, $city)) {
          $city[] = $area->items[$i]->city;
        }

        if (!in_array($area->items[$i]->county, $county)) {
          $county[] = $area->items[$i]->county;
        }

        if (!in_array($area->items[$i]->state, $state)) {
          $state[] = $area->items[$i]->state;
        }

        if (!in_array($area->items[$i]->postalcode, $postalcode)) {
          $postalcode[] = $area->items[$i]->postalcode;
        }
      }

      $this->data = new \stdClass();

      if (count($city) > 1) {
        $this->data->city = $city;
      } else if (!empty($city)) {
        $this->data->city = $city[0];
      }

      if (count($county) > 1) {
        $this->data->county = $county;
      } else if (!empty($county)) {
        $this->data->county = $county[0];
      }

      if (count($state) > 1) {
        $this->data->state = $state;
      } else if (!empty($state)) {
        $this->data->state = $state[0];
      }

      if (count($postalcode) > 1) {
        $this->data->postalcode = $postalcode;
      } else if (!empty($postalcode)) {
        $this->data->postalcode = $postalcode[0];
      }
    }
  }
}
