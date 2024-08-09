<?php

$this->head->meta->add('viewport', 'width=device-width, initial-scale=1, user-scalable=no');
$this->head->link->add('https://fonts.googleapis.com', 'preconnect');
$this->head->link->add('https://fonts.gstatic.com', 'preconnect', ['crossorigin' => '']);
$this->head->css->addFile('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;500&family=Roboto:ital,wght@0,400;0,700;1,400&display=swap');

$this->head->css->addFile('fa.min.css');
//$this->head->javascript->addFile('template.primary.js');

echo '<!doctype html>';
echo '<html lang="en">';

echo '<head>';
echo '{{head}}';
echo '</head>';

echo '<body>';
echo '<div id="site">';

echo '<header id="head">';
echo '<i id="showMenu" class="fas fa-bars"></i>';
echo '<h1>{{title}}</h1>';
echo '<i id="showSettings" class="fas fa-cog"></i>';
echo '</header>';

echo '<div id="controls">{{module:Admin/Controls}}</div>';

echo '<div id="menu">';
echo '<div class="top">';
echo '<img src="img/logo.svg" width="36" height="30"> ';
echo '<h1>My Travel</h1>';
echo '<i class="fa-solid fa-circle-xmark close"></i>';
echo '</div>';
echo '{{module:Menu:menu=Main Menu}}';
echo '</div>';

echo '<main>{{component}}</main>';

echo '<footer></footer> ';

echo '</div>';
echo '</body>';
echo '</html>';
