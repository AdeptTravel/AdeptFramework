<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

// Turn debug on/off
define('DEBUG',                    true);

// Get's the current working directory for defines.php.  This is the root dir
define('FS_PATH',                   realpath(__DIR__ . '/..') . '/');
define('FS_CSS',                    FS_PATH . 'css/');
define('FS_JS',                     FS_PATH . 'js/');
define('FS_IMG',                    FS_PATH . 'img/');
define('FS_LOG',                    FS_PATH . 'log/');
define('FS_MEDIA',                  FS_PATH . 'media/');
define('FS_SRC',                    FS_PATH . 'src/');
define('FS_TEMPLATE',               FS_PATH . 'template/');

define('FS_CACHE',                  FS_SRC . 'Cache/');
define('FS_COMPONENT',              FS_SRC . 'Component/');
define('FS_CSV',                    FS_SRC . 'Resources/CSV/');
define('FS_SQL',                    FS_SRC . 'Resources/SQL/');


// Set the error handler to catch compile errors as exceptions
set_error_handler(function ($severity, $message, $file, $line) {
  if (error_reporting() & $severity) {
    //throw new ErrorException($message, 0, $severity, $file, $line);
    die("MEssage: $message<br>Severity: $severity<br>File: $file<br>Line: $line");
  }
});

// Enable error reporting for all errors, including compile errors
error_reporting(E_ALL);

// Enable throwing errors as exceptions

ini_set('display_errors', 1);
ini_set('log_errors', '1');
ini_set('error_log', FS_LOG . 'php-errors.log'); // Optional: Log errors to a file

// Example code that may trigger a compile error
// require 'nonexistent_file.php';

// Restore the default error handler
restore_error_handler();
