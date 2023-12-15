<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

// Turn debug on/off
define('DEBUG',                    true);

// Get's the current working directory for defines.php.  This is the root dir
define('FS_PATH',                   realpath(__DIR__ . '/../../../..') . '/');
define('FS_SYS',                    FS_PATH . 'sys/');

// Publically available paths
define('FS_AUDIO',                  FS_PATH . 'audio/');
define('FS_CSS',                    FS_PATH . 'css/');
define('FS_IMG',                    FS_PATH . 'img/');
define('FS_JS',                     FS_PATH . 'js/');
define('FS_VIDEO',                  FS_PATH . 'video/');

// System specific
define('FS_CACHE',                  FS_SYS . 'Cache/');
define('FS_COMPONENT',              FS_SYS . 'Component/');
define('FS_LOG',                    FS_SYS . 'Log/');
define('FS_MEDIA',                  FS_SYS . 'Media/');
define('FS_TEMPLATE',               FS_SYS . 'Template/');
