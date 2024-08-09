<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

// Turn debug on/off
define('DEBUG',                    true);

// Get's the current working directory for defines.php.  This is the root dir
define('FS_CORE',                    __DIR__ . '/');
define('FS_SITE',                   realpath(FS_CORE . '../../../..') . '/');
// Publically available paths
define('FS_AUDIO',                  FS_SITE . 'audio/');
define('FS_CSS',                    FS_SITE . 'css/');
define('FS_IMG',                    FS_SITE . 'img/');
define('FS_JS',                     FS_SITE . 'js/');
define('FS_VIDEO',                  FS_SITE . 'video/');
define('FS_FONT',                   FS_SITE . 'webfont/');
// Site specific paths
define('FS_SITE_CACHE',             FS_SITE . 'site/Cache/');
define('FS_SITE_COMPONENT',         FS_SITE . 'site/Component/');
define('FS_SITE_MEDIA',             FS_SITE . 'site/Media/');
define('FS_SITE_MODULE',            FS_SITE . 'site/Module/');
define('FS_SITE_TEMPLATE',          FS_SITE . 'site/Template/');
// System specific paths
define('FS_CORE_COMPONENT',         FS_CORE . 'Component/');
define('FS_CORE_MODULE',            FS_CORE . 'Module/');
define('FS_CORE_TEMPLATE',          FS_CORE . 'Template/');
// Status
define('ITEM_STATUS_OFF',           0);
define('ITEM_STATUS_ON',            1);
define('ITEM_STATUS_ARCHIVE',       2);
define('ITEM_STATUS_TRASH',         3);
define('ITEM_STATUS_MISSING',       4);
