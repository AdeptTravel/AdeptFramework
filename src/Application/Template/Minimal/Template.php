<?php

$head->meta->add('viewport', 'width=device-width, initial-scale=1, user-scalable=no');
$head->link->add('https://fonts.googleapis.com', 'preconnect');
$head->link->add('https://fonts.gstatic.com', 'preconnect', ['crossorigin' => '']);
$head->css->addFile('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;500&family=Roboto:ital,wght@0,400;0,700;1,400&display=swap');

$head->css->addFile('/css/fa.min.css');
//$head->css->addFile('/css/global.css');
//$head->css->addFile('/css/template.minimal.css');
//$head->css->addFile('/css/form.css');
//$head->css->addFile('/css/type.css');

echo '<!DOCTYPE html>';
echo '<html lang="en-us" dir="ltr">';

echo '<head>{{head}}</head>';

echo '<body> ';
echo '<div class="centerAll">';
echo '<main class="container"> ';
echo '<div class="logo"><img src="/img/logo.svg" alt="The Adept Traveler Brandmark" width="144" height="120"></div>';
echo '{{component}}';
echo '</main>';
echo '</div> ';
echo '</body>';

echo '</html>';
