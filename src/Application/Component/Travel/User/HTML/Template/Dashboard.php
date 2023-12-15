<?php

// Shortcut to the user object
$user = $this->app->session->auth->user;

echo '<h1>My Profile</h1>';
echo '<article>';

echo '<heading>';
echo '<h3>Information</h3>';
echo '</heading>';

echo '<div>';
echo '<dt>Name</dt>';
echo "<dd>$user->firstname $user->middlename $user->lastname</dd>";
echo '</div>';

echo '<div>';
echo '<dt>Email Address</dt>';
echo '<dd>' . $user->username . '</dd>';
echo '</div>';

echo '<div>';
echo '<dt>Date of Birth</dt>';
echo '<dd>' . $user->dob->format('y-m-d') . '</dd>';
echo '</dl>';
echo '</article>';
