<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Turn debug on/off
define('DEBUG',                    true);

// Get's the current working directory for defines.php.  This is the root dir
define('FS_SITE',                   getcwd() . '/');
define('FS_CSS',                    FS_SITE . 'css/');
define('FS_JS',                     FS_SITE . 'js/');
define('FS_IMG',                    FS_SITE . 'img/');
define('FS_SRC',                    FS_SITE . 'src/');

define('FS_SITE_CACHE',                  FS_SRC . 'Cache/');
define('FS_COMPONENT',              FS_SRC . 'Component/');
define('FS_LOG',                    FS_SRC . 'Log/');
define('FS_CSV',                    FS_SRC . 'Resources/CSV/');
define('FS_SQL',                    FS_SRC . 'Resources/SQL/');
define('FS_TEMPLATE',               FS_SRC . 'Template/');

define('STATUS_FAIL',               0);
define('STATUS_SUCCESS',            0);
define('STATUS_WARN',               0);

// Frequently used SQL 
define('SQL_PUBLISHED',             " AND publish = 1 AND publish < NOW() AND (archive > NOW() OR archive = '0000-00-00 00:00:00')");
define('SQL_PUBLISHED_A',           " AND a.publish = 1 AND a.publish < NOW() AND (a.archive > NOW() OR a.archive = '0000-00-00 00:00:00')");
define('SQL_PUBLISHED_B',           " AND b.publish = 1 AND b.publish < NOW() AND (b.archive > NOW() OR b.archive = '0000-00-00 00:00:00')");
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
