<?php

/**
 * \AdeptCMS\Application\Session\Request\Data
 *
 * Data used for the current request
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Application\Session\Request;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Application\Session\Request\Data
 *
 * Data used for the current request
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Data
{
  /**
   * @var int
   */
  public $id;

  /**
   * @var object
   */
  public $data;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public $form;

  public function __construct(\AdeptCMS\Application\Database &$db)
  {
    $this->id = 0;
    $this->form = [];

    if (count($_POST) > 0) {
      $data = filter_input_array(INPUT_POST);

      if (is_array($data)) {
        ksort($data);
        $this->form = $data;
      }

      $this->id = $db->insertSingleTableGetId(
        'request_data',
        [
          'data' => json_encode($this->form)
        ]
      );

      $this->data = new \stdClass();

      foreach ($data as $key => $value) {

        if (strpos($key, '_') === false) {
          $this->data->$key = $value;
        } else {
          $parts = explode('_', $key);

          //if (count($parts) > 2) {
          $ref = &$this->data;

          for ($p = 2; $p < count($parts); $p++) {
            $k = $parts[$p];
            if (isset($ref->$k)) {
            } else {
              if ($p == count($parts) - 1) {
                //echo "<div>Setting $k = $value</div>";
                $ref->$k = $value;
              } else {
                $ref->$k = new \stdClass();
              }
            }

            $ref = &$ref->$k;
            //}
          }
        }
      }
    }
  }

  /**
   * Undocumented function
   *
   * @param string $key
   * @param integer $filter
   * 
   * @ref https://www.php.net/manual/en/filter.filters.sanitize.php
   * 
   * @return string|int|bool|float
   */
  public function getValue(string $key, int $filter = FILTER_UNSAFE_RAW): string|int|bool|float|null
  {
    $data = null;

    if (array_key_exists($key, $this->form)) {
      $data = $this->form[$key];
    }

    return $data;
  }

  public function getBool(string $key, int $filter = FILTER_SANITIZE_NUMBER_INT): bool|null
  {
    $data = $this->getValue($key, $filter);
    return ($data != null) ? (bool)$data : null;
  }

  public function getString(string $key, int $filter = FILTER_UNSAFE_RAW): string|null
  {
    $data = $this->getValue($key, $filter);
    return ($data != null) ? (string)$data : '';
  }

  public function getInt(string $key, int $filter = FILTER_SANITIZE_NUMBER_INT, int $default = 0): int|null
  {
    $data = $this->getValue($key, $filter);
    return ($data != null) ? (int)$data : null;
  }

  public function getJSON(string $key): object|array
  {
    $raw = $this->getString($key);
    $json = json_decode($raw);
    return $json;
  }
}
