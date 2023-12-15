<?php

$head->meta->add('viewport', 'width=device-width, initial-scale=1, user-scalable=no');
$head->link->add('https://fonts.googleapis.com', 'preconnect');
$head->link->add('https://fonts.gstatic.com', 'preconnect', ['crossorigin' => '']);
$head->css->addFile('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;500&family=Roboto:ital,wght@0,400;0,700;1,400&display=swap');

$head->css->addFile('/css/fa.min.css');
//$head->css->addFile('/css/global.css');
//$head->css->addFile('/css/template.primary.css');
//$head->css->addFile('/css/form.css');
//$head->css->addFile('/css/type.css');

//$head->javascript->addFile('/js/template.primary.js');
//$head->javascript->addFile('/js/form.js');

echo '<!doctype html>';
echo '<html lang="en">';

echo '<head>';
echo '{{head}}';
echo '</head>';


echo '<body>';
echo '<div id="site">';
echo '<header id="head">';

if ($auth->status) {
  echo '<i id="showMenu" class="fas fa-bars"></i>';
}

echo '<h1>{{title}}</h1>';

if ($auth->status) {
  echo '<i id="showSettings" class="fas fa-cog"></i>';
}

echo '</header>';

echo '<div id="menu">';
echo '<div id="brand"><a href="/admin/" title="YCMS Administration"><img src="/admin/img/logo.svg" alt="YCMS Administration"></a></div>';

if ($auth->status) {
  echo '<div class="main">{{menu:main}}</div>';
}

echo '</div>';

echo '<div id="component">';
echo '<main>{{component}}</main>';
echo '</div>';

echo '<div id="settings">';
echo '</div>';
echo '</div>';
echo '</body>';
echo '</html>';
