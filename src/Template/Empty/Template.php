<?php

$this->head->meta->add('viewport', 'width=device-width, initial-scale=1, user-scalable=no');
$this->head->link->add('https://fonts.googleapis.com', 'preconnect');
$this->head->link->add('https://fonts.gstatic.com', 'preconnect', ['crossorigin' => '']);
$this->head->css->addFile('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;500&family=Roboto:ital,wght@0,400;0,700;1,400&display=swap');

$this->head->css->addFile('fa.min.css');

echo '<!DOCTYPE html>';
echo '<html lang="en-us" dir="ltr">';

echo '<head>{{head}}</head>';

echo '<body> ';
echo '<main> ';
echo '{{component}}';
echo '</main>';
echo '</body>';

echo '</html>';
