<?php

namespace Adept\Document\HTML\Body;

defined('_ADEPT_INIT') or die();

use \Adept\Application;

class Status
{
  public array $success = [];
  public array $information = [];
  public array $error = [];
  public array $warning = [];

  /**
   * Add a success message
   *
   * @param  string $title
   * @param  string $message
   *
   * @return void
   */
  public function addSuccess(string $title, string $message)
  {
    $this->success[] = (object)[
      'title' => $title,
      'message' => $message
    ];
  }

  /**
   * Undocumented function
   *
   * @param  string $title
   * @param  string $message
   *
   * @return void
   */
  public function addError(string $title, string $message)
  {
    $this->error[] = (object)[
      'title' => $title,
      'message' => $message
    ];
  }

  /**
   * Add a warning status message
   *
   * @param  string $title
   * @param  string $message
   *
   * @return void
   */
  public function addWarning(string $title, string $message)
  {
    $this->warning[] = (object)[
      'title' => $title,
      'message' => $message
    ];
  }

  /**
   * Add informational status, this is neutral info not success/warn/fail
   *
   * @param  string $title
   * @param  string $message
   *
   * @return void
   */
  public function addInformation(string $title, string $message)
  {
    $this->information[] = (object)[
      'title' => $title,
      'message' => $message
    ];
  }

  /**
   * Returns HTML with all the status messages
   *
   * @return string
   */
  public function getBuffer(): string
  {
    $html = '<div class="status">';

    foreach (['error', 'warning', 'success', 'information'] as $type) {
      if (!empty($this->$type)) {
        for ($i = 0; $i < count($this->$type); $i++) {
          $html .= '<div class="' . $type . '">';
          $html .= '<div class="icon">';

          switch ($type) {
            case 'error':
              $html .= '<i class="fa-solid fa-circle-xmark"></i>';
              break;
            case 'warning':
              $html .= '<i class="fa-solid fa-triangle-exclamation"></i>';
              break;
            case 'success':
              $html .= '<i class="fa-solid fa-circle-check"></i>';
              break;
            case 'information':
              $html .= '<i class="fa-solid fa-circle-info"></i>';
              break;
          }
          $html .= '</div>';

          $html .= '<div class="msg"><strong>' . $this->$type[$i]->title . '</strong> • ' . $this->$type[$i]->message . '</div>';
          $html .= '<div class="close"><i class="fa-solid fa-xmark"></i></div>';
          $html .= '</div>';
        }
      }
    }

    $html .= '</div>';

    return $html;
  }
}
