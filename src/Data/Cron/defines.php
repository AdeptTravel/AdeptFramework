<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Turn debug on/off
define('DEBUG',                    true);

// Get's the current working directory for defines.php.  This is the root dir
define('FS_PATH',                   getcwd() . '/');
define('FS_CSS',                    FS_PATH . 'css/');
define('FS_JS',                     FS_PATH . 'js/');
define('FS_IMG',                    FS_PATH . 'img/');
define('FS_SRC',                    FS_PATH . 'src/');

define('FS_CACHE',                  FS_SRC . 'Cache/');
define('FS_COMPONENT',              FS_SRC . 'Component/');
define('FS_LOG',                    FS_SRC . 'Log/');
define('FS_CSV',                    FS_SRC . 'Resources/CSV/');
define('FS_SQL',                    FS_SRC . 'Resources/SQL/');
define('FS_TEMPLATE',               FS_SRC . 'Template/');

define('STATUS_FAIL',               0);
define('STATUS_SUCCESS',            0);
define('STATUS_WARN',               0);

// Frequently used SQL 
define('SQL_PUBLISHED',             " AND publish = 1 AND publish_start < NOW() AND (publish_end > NOW() OR publish_end = '0000-00-00 00:00:00')");
define('SQL_PUBLISHED_A',           " AND a.publish = 1 AND a.publish_start < NOW() AND (a.publish_end > NOW() OR a.publish_end = '0000-00-00 00:00:00')");
define('SQL_PUBLISHED_B',           " AND b.publish = 1 AND b.publish_start < NOW() AND (b.publish_end > NOW() OR b.publish_end = '0000-00-00 00:00:00')");
// Defaults
define('YCOMPONENT_SITE_DEFAULT',   'Default');

//function error(string $class, string $function, int $line, string $message)
function error(array $arr, string $title, string $message)
{
  if (DEBUG) {
    echo '<h3>' . $title . '</h3>';
    echo '<p>' . $message . '</p>';
    echo '<pre>';
    print_r($arr);

    //file_put_contents(FS_LOG . 'error.log', $err, FILE_APPEND);
  }

  die();
}
