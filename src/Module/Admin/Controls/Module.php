<?php

defined('_ADEPT_INIT') or die();

use Adept\Application;

$app = Application::getInstance();

$map = (object)[
  'close'     => (object)['fa' => 'fa-xmark', 'title' => 'Close'],
  'delete'    => (object)['fa' => 'fa-trash-can', 'title' => 'Delete'],
  'duplicate' => (object)['fa' => 'fa-regular fa-clone', 'title' => 'Duplicate'],
  'edit'      => (object)['fa' => 'fa-pen-to-square', 'title' => 'Edit'],
  'new'       => (object)['fa' => 'fa-plus', 'title' => 'New'],
  'publish'   => (object)['fa' => 'fa-check', 'title' => 'Publish'],
  'save'      => (object)['fa' => 'fa-regular fa-floppy-disk', 'title' => 'Save'],
  'saveclose' => (object)['fa' => 'fa-regular fa-floppy-disk', 'title' => 'Save &amp; Close'],
  'savecopy'  => (object)['fa' => 'fa-regular fa-copy', 'title' => 'Save &amp; Copy'],
  'savenew'   => (object)['fa' => 'fa-regular fa-file-circle-plus', 'title' => 'Save &amp; New'],
  'unpublish' => (object)['fa' => 'fa-regular fa-xmark', 'title' => 'Unpublish'],
  'upload'    => (object)['fa' => 'fa-solid fa-upload', 'title' => 'Upload'],
  'newdir'    => (object)['fa' => 'fa-solid fa-folder-plus', 'title' => 'Create Directory']
];

$buffer = '';

$reflect = new \ReflectionClass($app->conf->component->controls);
$properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
$js = false;

foreach ($properties as $p) {

  $key = $p->name;

  if ($app->conf->component->controls->$key) {

    if (!$js) {
      $app->html->head->javascript->addFile('form.controls.js');
      $js = true;
    }

    $buffer .= '<button';
    $buffer .= ' type="submit"';
    $buffer .= ' class="' . $key . '"';
    $buffer .= ' data-action="' . $key . '"';
    $buffer .= '>';
    $buffer .= '<i class="fa-solid ';
    $buffer .= $map->$key->fa;
    $buffer .= '"></i>';
    $buffer .= '<span>' . $map->$key->title . '</span>';
    $buffer .= '</button>';
  }
}

echo $buffer;
