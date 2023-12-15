<?php

$this->doc->head->css->addFile('/css/component/cms/media/image.css');
//$this->doc->head->css->addFile('/css/tabs.css');
//$this->doc->head->javascript->addFile('/js/form.ajax.js');

echo '<article>';
echo '<h1>Image Library</h1>';

// TODO - Make this filter a standalone thing, maybe a form overload?
echo '<section class="filter">';
echo '<form class="full">';
echo '<input type="text" placeholder="Search">';
echo '<select name="status">';
echo '<option value="" selected>All</option>';
echo '<option value="1">Published</option>';
echo '<option value="0">Unpublished</option>';
echo '</select>';
echo '</form>';
echo '</section>';

echo '<section class="full medialist">';

//die('<pre>' . print_r($this->data->items, true));
/*
  [id] => 3
  [type] => Image
  [mime] => image/jpeg
  [path] => Image/Today in Travel
  [file] => Image/Today in Travel/Japan.jpg
  [alias] => today-in-travel/japan
  [extension] => jpg
  [width] => 1920
  [height] => 1080
  [duration] => 0
  [size] => 1864698
  [title] => 
  [caption] => 
  [summary] => 
  [description] => 
  [created] => 2023-12-12 14:00:33
  [modified] => 2023-12-12 14:00:33
  [status] => 1
*/

for ($i = 0; $i < count($this->data->items); $i++) {
  $file = '/img/' . $this->data->items[$i]->alias . '-135x135dpi72ql85.webp';

  echo '<div>';
  //echo '<h4>' . $this->data->items[$i]->title . '</h4>';
  echo '<img src="' . $file . '">';
  echo '<p class="small">' . $this->data->items[$i]->title . '</p>';
  //echo '<div class="controls">';
  //echo '<i class="fa-solid fa-circle-info"></i>';
  //echo '<i class="fa-solid fa-trash"></i>';
  //echo '</div>';
  echo '</div>';
}

echo '</section>';
echo '</article>';
