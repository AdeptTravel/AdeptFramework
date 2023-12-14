<?php

define('_ADEPT_INIT', 1);

require_once('../../vendor/autoload.php');
require_once('../../defines.php');
require_once('../../configuration.php');

$app = new \Adept\Cron(new Configuration());

$grids = $app->db->getObjects(
  'SELECT * FROM weather_grid',
  []
);

for ($g = 0; $g < count($grids); $g++) {
  $url = 'https://api.weather.gov/gridpoints/' . $grids[$g]->o . '/' . $grids[$g]->x . ',' . $grids[$g]->y . '/forecast';
  $json = $app->cURL->get($url);

  $data = json_decode($json);
  $periods = $data->properties->periods;

  for ($p = 0; $p < count($periods); $p++) {
    $start = new DateTime($periods[$p]->startTime);
    $end = new DateTime($periods[$p]->endTime);

    $query = "SELECT COUNT(*) FROM `weather` WHERE `grid` = ? AND `start` = ? AND `gap` = ?";
    $params = [
      $grids[$g]->id,
      $start->format('Y-m-d H:i:s'),
      $p
    ];

    $count = $app->db->getInt($query, $params);

    if ($count == 0) {
      $app->db->insert(
        "INSERT INTO weather (`grid`,`start`,`end`,`gap`,`temperature`,`precipitation`,`humiditity`,`wind_speed`,`wind_direction`,`description`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [
          $grids[$g]->id,
          $start->format('Y-m-d H:i:s'),
          $end->format('Y-m-d H:i:s'),
          $p,
          $periods[$p]->temperature,
          ((empty($periods[$p]->probabilityOfPrecipitation->value)) ? 0 : $periods[$p]->probabilityOfPrecipitation->value),
          $periods[$p]->relativeHumidity->value,
          $periods[$p]->windSpeed,
          $periods[$p]->windDirection,
          $periods[$p]->detailedForecast
        ]
      );
    }
  }
}
