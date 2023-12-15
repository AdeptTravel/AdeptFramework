<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

// Turn debug on/off
//define('DEBUG',                    true);

// Get's the current working directory for defines.php.  This is the root dir
define('FS_SYS',                    __DIR__ . '/');
define('FS_PATH',                   realpath(FS_SYS . '../../../..') . '/');
define('FS_SITE',                   FS_PATH . 'site/');

// Publically available paths
define('FS_AUDIO',                  FS_PATH . 'audio/');
define('FS_CSS',                    FS_PATH . 'css/');
define('FS_IMG',                    FS_PATH . 'img/');
define('FS_JS',                     FS_PATH . 'js/');
define('FS_VIDEO',                  FS_PATH . 'video/');

// Site specific paths
define('FS_SITE_CACHE',             FS_SITE . 'Cache/');
define('FS_SITE_LOG',               FS_SITE . 'Log/');
define('FS_SITE_MEDIA',             FS_SITE . 'Media/');
define('FS_SITE_COMPONENT',         FS_SITE . 'Component/');
define('FS_SITE_TEMPLATE',          FS_SITE . 'Template/');

// System specific paths
define('FS_SYS_COMPONENT',          FS_SYS . 'Component/');
define('FS_SYS_TEMPLATE',           FS_SYS . 'Template/');
