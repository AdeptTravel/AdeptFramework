<?php

$html  = '<article>';
$html .= '<header>';
$html .= '<h1>' . $this->item->title . '</h1>';
$html .= '</header>';

$html .= '<section class="content">';
$html .= $this->item->content;
$html .= '</section>';

if (!empty($this->item->tags)) {
  $html .= '<section class="tags">';

  for ($i = 0; $i < count($this->item->tags); $i++) {
    $html .= '<article>';
    $html .= '<a href="/' . $this->item->tags[$i]->route . '">' . $this->item->tags[$i]->title . '</a>';
    $html .= '</article>';
  }

  $html .= '</section>';
}

if (!empty($this->item->articles)) {
  $html .= '<section class="articles">';

  for ($i = 0; $i < count($this->item->tags); $i++) {
    $html .= '<article>';

    $html .= '<figure>';
    $html .= '<a href="/' . $this->item->tags[$i]->route . '">';
    $html .= $this->item->tags[$i];
    $html .= '</a>';
    $html .= '</figure>';

    $html .= '<h3><a href="/' . $this->item->tags[$i]->route . '">';
    $html .= $this->item->tags[$i]->title;
    $html .= '</a></h3>';

    $html .= $this->item->tags[$i]->summary;


    $html .= '</article>';
  }

  $html .= '</section>';
}

echo $html;
