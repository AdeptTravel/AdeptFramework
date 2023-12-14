<?php

namespace Adept\Model\Items;

defined('_ADEPT_INIT') or die('No Access');

class Url extends \Adept\Abstract\Model\Item
{

  public function __construct(\Adept\Application\Database &$db, string $url = '')
  {
    parent::__construct($db);

    $this->init();

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";

    $host = (!empty($_SERVER['HTTP_HOST']))
      ? $_SERVER['HTTP_HOST']
      : $_SERVER['SERVER_NAME'];

    // No url specified, set current url
    if (empty($url)) {
      $url = $scheme . '://' . $host . $_SERVER['REQUEST_URI'];
    }

    // Check if scheme or host are missing
    if (($pos = strpos($url, '/') == 0) && $pos !== false) {
      // Missing host
      if (($pos = strpos($url, '//') == 0) && $pos !== false) {
        $url = $scheme . ':' . $url;
      } else {
        $url = $scheme . '://' . $host . $url;
      }
    }

    $url = filter_var($url, FILTER_SANITIZE_URL);

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      throw new \Adept\Exceptions\InvalidUrl();
    }

    // Remove anchor
    if ($pos = strpos($url, '#')) {
      $url = substr($url, 0, $pos);
    }

    // Remove query
    if ($pos = strpos($url, '?')) {
      $url = substr($url, 0, $pos);
    }

    $this->cache = FS_CACHE . 'Url/' . hash('md5', $url) . '.json';

    if ($this->loadCache()) {
      $this->data->parts = json_decode($this->data->parts);
    } else {
      $pos = strpos($url, '://');
      $this->data->scheme = substr($url, 0, $pos);

      $pos += 3;
      $this->data->host = substr($url, $pos, strpos($url, '/', $pos) - $pos);

      $pos += strlen($this->data->host);
      $this->data->path = substr($url, $pos);

      if (strlen($this->data->path) > 1) {
        $this->data->parts = explode('/', substr($this->data->path, 1));
      }

      $last = count($this->data->parts) - 1;

      if ($last == 0 && $this->data->parts[0] == 'admin') {
        $this->data->file = 'index.html';
        $this->data->path .= '/index.html';
        $this->data->extension = 'html';
        $this->data->parts[] = $this->data->file;
      } else if ($last >= 0 && !empty($this->data->parts[$last])) {
        $this->data->file = $this->data->parts[$last];

        if (($pos = strpos($this->data->file, '.')) !== false) {
          $this->data->extension = substr($this->data->file, $pos + 1);
        } else {
          $this->data->file .= '.html';
          $this->data->path .= '.html';
          $this->data->parts[$last] .= '.html';
          $this->data->extension = 'html';
        }
      } else {

        unset($this->data->parts[$last]);

        $this->data->file = 'index.html';
        $this->data->path .= 'index.html';
        $this->data->extension = 'html';
        $this->data->parts[] = $this->data->file;
      }

      $info = $this->getFormatInfo($this->data->extension);

      if ($info !== false) {
        $this->data->type = $info->type;
        $this->data->mime = $info->mime;
      } else {
        // TODO: Format error
        die('');
      }

      $this->save();
    }
  }

  public function init()
  {
    $this->data = (object)[
      'id' => 0,
      'url' => '',
      'scheme' => '',
      'host' => '',
      'path' => '',
      'parts' => [],
      'file' => '',
      'extension' => '',
      'type' => '',
      'mime' => ''
    ];
  }

  public function getExtension(): string
  {
    return $this->data->extension;
  }

  public function getFile(): string
  {
    return $this->data->file;
  }

  public function getHost(): string
  {
    return $this->data->host;
  }

  public function getId(): int
  {
    return $this->data->id;
  }

  public function getMime()
  {
    return $this->data->mime;
  }

  public function getPart(int $index): string
  {
    return ($index < count($this->data->parts))
      ? $this->data->parts[$index]
      : '';
  }

  public function getParts(): array
  {
    return $this->data->parts;
  }

  public function getPath(): string
  {
    return $this->data->path;
  }

  public function getScheme(): string
  {
    return $this->data->scheme;
  }

  public function getType(): string
  {
    return $this->data->type;
  }

  public function getUrl(): string
  {
    return $this->data->url;
  }

  public function isEmpty(): bool
  {
    return empty($this->data->url);
  }

  public function toString(): string
  {
    return $this->getUrl();
  }

  protected function validate(): bool
  {
    return $this->isEmpty();
  }

  public function save(): bool
  {
    $status = false;

    if ($this->validate()) {
      $data = $this->data;

      $data->parts = json_encode($data->parts);

      if ($data->id == 0) {
        unset($data->id);
      }

      $data = (array)$data;

      if (($id = $this->db->insertSingleTableGetId('url', $data)) !== false) {
        $this->data->id = $id;
        $this->saveCache();

        $status = true;
      }
    }

    return $status;
  }
}
