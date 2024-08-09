<?php

namespace Adept\Document\HTML\Head;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration;
use \Adept\Application\Session\Request;

class Meta
{

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $meta;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $key;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $title = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $description = '';

  /**
   * Undocumented function
   */
  public function __construct()
  {

    $app = \Adept\Application::getInstance();
    $conf = $app->conf;
    $request = $app->session->request;

    $this->meta = [];

    $this->key = [
      'http-equiv' => [],
      'itemprop' => [],
      'name' => [],
      'property' => []
    ];
    //charset = "utf-8"
    $this->key['http-equiv'] = ['content-type'];

    $this->key['itemprop'] = ['name'];

    $this->key['name'] = [
      'copyright',
      'description',
      'generator',
      'keywords',
      'viewport'
    ];

    $this->key['property'] = [
      'og:audio',
      'og:audio:secure_url',
      'og:audio:type',
      'og:description',
      'og:determiner',
      'og:image',
      'og:image:alt',
      'og:image:height',
      'og:image:secure_url',
      'og:image:type',
      'og:image:width',
      'og:locale',
      'og;locale:alternate',
      'og:site_name',
      'og:title',
      'og:type',
      'og:url',
      'og:video',
      'og:video:height',
      'og:video:secure_url',
      'og:video:type',
      'og:video:width',
      'twitter:card',
      'twitter:image:src',
      'twitter:site',
      'twitter:title',
      'twitter:site:id',
      'twitter:description',
      'twitter:url'
    ];

    $this->add('content-type', 'text/html; charset=utf-8');
    $this->add('generator', $conf->app->name);
    $this->add('og:locale', 'en_US');
    $this->add('og:type', 'website');

    if (isset($conf->share)) {
      $this->add('twitter:site:id', '@' . $conf->share->twitter->username);
      $this->add('twitter:url', $request->url->url);
    }
  }

  public function add(string $name, string $content)
  {
    $this->meta[$this->clean($name)] = $this->clean($content);
  }


  public function getBuffer(): string
  {
    $conf = \Adept\Application::getInstance()->conf;
    $html = '';

    if (!empty($this->title)) {
      $title = $this->title . ' - ' . $conf->site->name;
      if (isset($conf->share)) {
        $this->add('og:title', $title);
        $this->add('twitter:title', $this->trimToChar($title, 70));
      }
    }

    if (!empty($this->description)) {
      $this->add('description', $this->trimToChar($this->description, 160));

      if (isset($conf->share)) {
        $this->add('og:description', $this->trimToChar($this->description, 110));
        $this->add('twitter:description', $this->trimToChar($this->description, 200));
      }
    }

    $html .= '<meta charset="utf-8">';

    foreach ($this->meta as $key => $value) {
      $html .= '<meta';

      if (in_array($key, $this->key['http-equiv'])) {
        $html .= ' http-equiv';
      } else if (in_array($key, $this->key['itemprop'])) {
        $html .= ' itemprop';
      } else if (in_array($key, $this->key['name'])) {
        $html .= ' name';
      } else if (in_array($key, $this->key['property'])) {
        $html .= ' property';
      }

      $html .= '="' . $key . '" content="' . $value . '">';
    }

    return $html;
  }

  public function clean(string $text): string
  {
    $text = strip_tags($text);
    $text = trim($text);
    return $text;
  }

  public static function trimToChar(string $text, int $length): string
  {
    if (strlen($text) > $length) {
      // Trim text to passed length
      $text = substr($text, 0, $length);

      // Get last space before end of string
      $pos = strrpos($text, ' ');

      // Go back to last word
      $text = substr($text, 0, $pos);

      // Trim back till less then $length - 3
      while (strlen($text) > ($length - 3)) {
        // Get last space before end of string
        $pos = strrpos($text, ' ');
        // Trim back to next to last word
        $text = substr($text, 0, $pos);
      }

      $text .= '...';
    }

    return $text;
  }
}
