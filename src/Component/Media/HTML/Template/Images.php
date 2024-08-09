<?php

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Status;
use Adept\Document\HTML\Elements\Form\DropDown;


// Shortcuts
$app      = Application::getInstance();
$head     = $app->html->head;
$request  = $app->session->request;
$get      = &$request->data->get;
$dirs     = $this->items->getDirs();
$list     = $this->items->getList();


$head->css->addFile('form.table.css');
$head->css->addFile('component/media.css');

$head->javascript->addFile('form.dropdown.js');
$head->javascript->addFile('form.filter.js');

$path = $request->url->path;
$sort = $get->getString('sort', '');
$dir  = $get->getString('dir', 'asc');

if (!empty($list)) {
  $form = new Form([
    'css' => ['filter'],
    'method' => 'get'
  ]);

  $form->children[] = new P(['text' => 'Filter']);

  //$filter = new Div(['css' => ['filter']]);

  $form->children[] = new Status([
    'name'        => 'status',
    'placeholder' => 'Status',
    'value'       => (string)(($get->exists('status')) ? $get->getInt('status', 0) : '')
  ]);

  $form->children[] = new DropDown([
    'label'        => 'Format',
    'name'         => 'mime',
    //'value'        => '',
    'placeholder' => 'Format',
    'allowEmpty'   => true,
    'emptyDisplay' => '-- Select Format --',
    'filter'       => false,
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

  //$form->children[] = $filter;

  echo $form->getBuffer();
}


echo '<div class="medialist">';

if ($get->exists('path')) {
  $path = $get->getString('path');

  if (strpos($path, '/') == strrpos($path, '/')) {
    $path = substr($path, 0, strrpos($path, '/'));
  } else {
    $path = '';
  }

  echo '<div class="dir">';
  echo '<a href="/media/image';

  if (!empty($path)) {
    echo '?path=' . $path;
  }

  echo '">';
  echo '<i class="fa-solid fa-circle-arrow-left"></i>';
  echo '<span>Back</span>';
  echo '</a>';
  echo '</div>';
}

for ($i = 0; $i < count($dirs); $i++) {
  echo '<div class="dir">';
  echo '<a href="/media/image?path=/' . $dirs[$i] . '">';
  echo '<i class="fa-solid fa-folder"></i>';
  echo '<span>' . $dirs[$i] . '</span>';
  echo '</a>';
  echo '</div>';
}

for ($i = 0; $i < count($list); $i++) {
  echo '<div>';

  echo '<a href="/media/image/edit?id=' . $list[$i]->id . '" title="Edit Image Information">';
  echo '<img src="/img/' . $list[$i]->alias . '-150x150.webp' . '"';

  switch ($list[$i]->status) {
    case ITEM_STATUS_OFF:
      echo ' class="unpublished"';
      break;
    case ITEM_STATUS_MISSING:
      echo ' class="missing"';
      break;
    default:
      break;
  }

  echo '>';
  echo '</a>';
  echo '<h3>' . $list[$i]->title . '</h3>';
  echo '<div class="info">' . $list[$i]->mime . ' - ' . $list[$i]->width . ' x ' . $list[$i]->height . '</div>';
  echo '</div>';
}
echo '</div>';
