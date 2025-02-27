<?php

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Helper\Arrays;

//$dirs     = $this->items->getDirs();
//$data     = $this->items->getList();

// Shortcuts
$app  = Application::getInstance();
$head = $app->html->head;
$get  = $app->session->request->data->get;
$url  = $app->session->request->url;
$path = $get->getString('path', '');

// Data
$table = $this->getTable();
$data  = $table->getData();
$dirs  = $table->getDirs();

$head->css->addAsset('Component/Select');

$head->javascript->addAsset('Component/Select');
$head->javascript->addAsset('Core/Form/Dropdown');
$head->javascript->addAsset('Core/Form/Filter');

//$path = $request->url->path;
$sort = $get->getString('sort', '');
$dir  = $get->getString('dir', 'asc');

$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'search',
  'placeholder' => 'Search',
  'value'       => $get->getString('search', '')
]);

$filter->children[] = new Dropdown([
  'name'        => 'status',
  'placeholder' => 'Status',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Status --',
  'value'       => (string)(($get->exists('status')) ? $get->getString('status', 0) : ''),
  'values'      => Arrays::ValueToArray(['Active', 'Archive', 'Inactive', 'Trash'])
]);

$filter->children[] = new DropDown([
  'label'        => 'Format',
  'name'         => 'mime',
  'value'        => $get->getString('mime'),
  'placeholder' => 'Format',
  'filter'       => false,
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Format --',
  'values'       => [
    'image/apng'                => 'apng',
    'image/bmp'                 => 'bmp',
    'image/gif'                 => 'gif',
    'image/vnd.microsoft.icon'  => 'ico',
    'image/jpeg'                => 'jpg',
    'image/png'                 => 'png',
    'image/tiff'                => 'tiff',
    'image/svg+xml'             => 'svg',
    'image/webp'                => 'webp'
  ]
]);

echo $filter->getBuffer();

echo '<div class="medialist">';

if (!empty($path)) {
  echo '<div class="dir">';
  echo '<a href="' . $url->path;
  if (strpos($path, '/') != strrpos($path, '/')) {
    echo '?path=' . substr($path, 0, strrpos($path, '/'));
  }

  echo '">';
  echo '<i class="fa-solid fa-circle-arrow-left"></i>';
  echo '<span>Back</span>';
  echo '</a>';
  echo '</div>';
}

for ($i = 0; $i < count($dirs); $i++) {
  echo '<div class="dir">';
  echo '<a href="' . $url->path . '?path=' . $path . '/' . $dirs[$i] . '">';
  echo '<i class="fa-solid fa-folder"></i>';
  echo '<span>' . $dirs[$i] . '</span>';
  echo '</a>';
  echo '</div>';
}
//die('<pre>' . print_r($data, true));
for ($i = 0; $i < count($data); $i++) {
  echo '<div class="select">';
  echo '<input type="hidden" name="id" value="' . $data[$i]->id . '">';
  echo '<input type="hidden" name="alias" value="' . $data[$i]->alias . '">';
  echo '<input type="hidden" name="thumbnail" value="/img/' . $data[$i]->alias . '-150x150.webp">';
  // TODO: Update for Image, Audio, and Video
  echo '<img src="/img/' . $data[$i]->alias . '-150x150.webp' . '"';

  switch ($data[$i]->status) {

    case 'Archive':
      echo ' class="archive"';
      break;

    case 'Inactive':
      echo ' class="inactive"';
      break;

    case 'Missing':
      echo ' class="missing"';
      break;

    default:
      break;
  }

  echo '>';
  echo '</a>';
  echo '<h3>' . $data[$i]->title . '</h3>';
  echo '<div class="info">' . $data[$i]->mime . ' - ' . $data[$i]->width . ' x ' . $data[$i]->height . '</div>';
  echo '</div>';
}
echo '</div>';
