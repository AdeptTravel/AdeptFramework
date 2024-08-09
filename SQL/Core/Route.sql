DROP TABLE IF EXISTS `Route`;
CREATE TABLE `Route` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route`      VARCHAR(256) NOT NULL UNIQUE,
  `redirect`   VARCHAR(256) DEFAULT'',
  `component`  VARCHAR(64) DEFAULT'',
  `option`     VARCHAR(64) DEFAULT'',
  `template`   VARCHAR(64) DEFAULT'',
  # Formats
  `html`       TINYINT(1) DEFAULT 0,
  `json`       TINYINT(1) DEFAULT 0,
  `xml`        TINYINT(1) DEFAULT 0,
  `csv`        TINYINT(1) DEFAULT 0,
  `pdf`        TINYINT(1) DEFAULT 0,
  `zip`        TINYINT(1) DEFAULT 0,
  # Include in Sitemap
  `sitemap`    TINYINT(1) DEFAULT 0,
  # Security Access
  `get`        TINYINT(1) DEFAULT 0,
  `post`       TINYINT(1) DEFAULT 0,
  `email`      TINYINT(1) DEFAULT 0,
  `secure`     TINYINT(1) DEFAULT 0,
  `cache`      TINYINT(1) DEFAULT 0,
  `status`     TINYINT(1) DEFAULT 1,
  `block`      TINYINT(1) DEFAULT 0,
  `created`    DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `Route` (`route`, `redirect`) VALUES
('', 'dashboard');

INSERT INTO `Route`
(`route`, `component`, `option`, `template`, `html`, `json`, `xml`, `csv`, `pdf`, `zip`, `sitemap`, `get`, `post`, `email`, `secure`, `status`, `block`)
VALUES
('login',                 'Auth',      'Login',          'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 0),
('logout',                'Auth',      'Logout',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0),
('forgot',                'Auth',      'Forgot',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 0),
('verify',                'Auth',      'Verify',         'Minimal', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 0),
('signup',                'User',      'Signup',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0),
('dashboard',             'Dashboard', 'Dashboard',      '',        1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0),
('route',                 'Route',     'Routes',         '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('route/edit',            'Route',     'Route',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('route/notfound',        'Route',     'NotFound',       '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('route/redirect',        'Route',     'Redirect',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('menu/',                 'Menu',      'Menus',          '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('menu/edit',             'Menu',      'Menu',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('menu/item',             'Menu',      'MenuItems',      '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('menu/item/edit',        'Menu',      'MenuItem',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('media/image',           'Media',     'Images',         '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('media/image/edit',      'Media',     'Image',          '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('content/article',       'Content',   'Articles',       '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/article/edit',  'Content',   'Article',        '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('content/category',      'Content',   'Categories',     '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/category/edit', 'Content',   'Category',       '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('content/tag',           'Content',   'Tags',           '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/tag/edit',      'Content',   'Tag',            '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0);


('user',                  'User',      'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0),
('user/edit',             'User',      'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0),
('user/cc',               'User',      'CreditCard',     '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0),
('user/id',               'User',      'Identification', '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0),
('user/program',          'User',      'Program',        '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0),
('content',               'Content',   'Dashboard',        '',      1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0),
('content/article',       'Content',   'Article',        '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/blog',          'Content',   'Blog',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/category',      'Content',   'Category',       '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/news',          'Content',   'News',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/tag',           'Content',   'Tag',            '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/edit',          'Content',   'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('content/media/audio',   'Media',     'Audio',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/media/image',   'Media',     'Image',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/media/video',   'Media',     'Video',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/media/edit',    'Media',     'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0),
('content/media/info',    'Media',     'Info',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0),
('content/feed',          'Feed',      'Feed',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0),
('content/feed/channel',  'Feed',      'Channel',        '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0),

### My Travel

INSERT INTO `route` (`route`, `redirect`) VALUES
('', 'dashboard');

INSERT INTO `route` (`route`, `category`, `component`, `option`, `public`, `template`, `get`, `post`, `email`) VALUES
('login',         'Core', 'Auth', 'Login',           1,'Minimal', 1, 1, 0),
('logout',        'Core', 'Auth', 'Logout',          1,'Minimal', 0, 0, 0),
('forgot',        'Core', 'Auth', 'Forgot',          1,'Minimal', 1, 1, 1),
('verify',        'Core', 'Auth', 'Verify',          1,'Minimal', 1, 1, 1),
('signup',        'User', 'Signup',          1,'Minimal', 0, 1, 1),
('dashboard',     'Dashboard', 'Dashboard',       0,'',        0, 0, 0),
('trip', 'Trip',  'Dashboard',       0,'',        0, 0, 0),
('user', 'User',  'Dashboard',       0,'',        0, 0, 0),
('user/edit',     'User', 'Edit',            0,'',        0, 1, 0),
('user/cc',       'User', 'CreditCard',      0,'',        0, 1, 0),
('user/id',       'User', 'Identification',  0,'',        0, 1, 0),
('user/program',  'User', 'Program',         0,'',        0, 1, 0),
('api/area',      'Location', 'Lookup',          1,'',        1, 0, 0);

### CMS

INSERT INTO `route` (`route`, `category`, `component`, `option`, `public`, `template`, `get`, `post`, `email`) VALUES
('content/article', 'CMS', 'Content', 'Article',  1,'', 1, 0, 0),
('content/blog', 'CMS', 'Content', 'Blog',     1,'', 1, 0, 0),
('content/category', 'CMS', 'Content', 'Category', 1,'', 1, 0, 0),
('content/news', 'CMS', 'Content', 'News',     1,'', 1, 0, 0),
('content/tag', 'CMS', 'Content', 'Tag',      1,'', 1, 0, 0),
('content/edit', 'CMS', 'Content', 'Edit',     1,'', 1, 1, 0),

('content/media/audio', 'CMS', 'Media', 'Audio',      1,'', 1, 0, 0),
('content/media/image', 'CMS', 'Media', 'Image',      1,'', 1, 0, 0),
('content/media/video', 'CMS', 'Media', 'Video',      1,'', 1, 0, 0),
('content/media/edit', 'CMS', 'Media', 'Edit',       1,'', 1, 1, 0),
('content/media/info', 'CMS', 'Media', 'Info',       1,'', 1, 0, 0),

#### Web

INSERT INTO `route` (`route`, `component`, `option`, `template`, `public`, `sitemap`) VALUES
('', 'Content', 'Article', 'Web', 1, 1),
('types', 'Content', 'Tags', 'Web', 1, 1),
('destinations', 'Content', 'Tags', 'Web', 1, 1),
('suppliers', 'Content', 'Tags', 'Web', 1, 1),
('travel-explained', 'Content', 'Category', 'Web', 1, 1),
('today-in-travel', 'Content', 'Category', 'Web', 1, 1),
('blogs', 'Content', 'Blogs', 'Web', 1, 1),
('podcasts', 'Content', 'Category', 'Web', 1, 1),
('policy/accessibility', 'Content', 'Article', 'Web', 1, 1),
('policy/cookie', 'Content', 'Article', 'Web', 1, 1),
('policy/privacy', 'Content', 'Article', 'Web', 1, 1),
('policy/terms-conditions', 'Content', 'Article', 'Web', 1, 1);
