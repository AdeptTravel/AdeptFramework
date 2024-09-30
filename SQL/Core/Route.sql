DROP TABLE IF EXISTS Redirect;
CREATE TABLE Redirect (
  `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `route`       VARCHAR(512) NOT NULL UNIQUE,
  `redirect`    VARCHAR(512) NOT NULL,
  `code`        SMALLINT UNSIGNED NOT NULL DEFAULT 301,
  `status`      ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
  `createdOn`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `Redirect` (`route`, `redirect`) VALUES
('', 'dashboard');


DROP TABLE IF EXISTS Route;
CREATE TABLE Route (
  `id`             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `route`          VARCHAR(512) NOT NULL UNIQUE,
  `component`      VARCHAR(64) DEFAULT NULL,
  `view`           VARCHAR(64) DEFAULT NULL,
  `template`       VARCHAR(64) DEFAULT NULL,
  -- Formats
  `html`           BOOLEAN DEFAULT FALSE,
  `json`           BOOLEAN DEFAULT FALSE,
  `xml`            BOOLEAN DEFAULT FALSE,
  `csv`            BOOLEAN DEFAULT FALSE,
  `pdf`            BOOLEAN DEFAULT FALSE,
  `zip`            BOOLEAN DEFAULT FALSE,
  -- Include in Sitemap
  `sitemap`        BOOLEAN DEFAULT FALSE,
  -- Security Access
  `allowGet`       BOOLEAN DEFAULT FALSE,
  `allowPost`      BOOLEAN DEFAULT FALSE,
  `allowEmail`     BOOLEAN DEFAULT FALSE,
  `isSecure`       BOOLEAN DEFAULT FALSE,
  `isCacheable`    BOOLEAN DEFAULT FALSE,
  -- General
  `status`         ENUM('Active', 'Block', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  `createdAt`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_component (`component`),
  INDEX idx_status (`status`)
);


INSERT INTO `Route`
(`route`, `component`, `view`, `template`, `html`, `json`, `xml`, `csv`, `pdf`, `zip`, `sitemap`, `allowGet`, `allowPost`, `allowEail`, `isSecure`, `status`)
VALUES
('login',                 'Auth',      'Login',          'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 'Active'),
('logout',                'Auth',      'Logout',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Active'),
('forgot',                'Auth',      'Forgot',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'Active'),
('verify',                'Auth',      'Verify',         'Minimal', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'Active'),
('signup',                'User',      'Signup',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 'Active'),
('dashboard',             'Dashboard', 'Dashboard',      '',        1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Active'),
('route',                 'Route',     'Routes',         '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('route/edit',            'Route',     'Route',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('route/notfound',        'Route',     'NotFound',       '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('route/redirect',        'Route',     'Redirect',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/',                 'Menu',      'Menus',          '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/edit',             'Menu',      'Menu',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/item',             'Menu',      'MenuItems',      '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/item/edit',        'Menu',      'MenuItem',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('media/image',           'Media',     'Images',         '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('media/image/edit',      'Media',     'Image',          '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/article',       'Content',   'Articles',       '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/article/edit',  'Content',   'Article',        '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/category',      'Content',   'Categories',     '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/category/edit', 'Content',   'Category',       '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/tag',           'Content',   'Tags',           '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/tag/edit',      'Content',   'Tag',            '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0);


INSERT INTO `Route`
(`route`, `component`, `view`, `template`, `html`, `json`, `xml`, `csv`, `pdf`, `zip`, `sitemap`, `allowGet`, `allowPost`, `allowEmail`, `isSecure`, `status`)
VALUES
('login',                 'Auth',      'Login',          'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 'Active'),
('logout',                'Auth',      'Logout',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Active'),
('forgot',                'Auth',      'Forgot',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'Active'),
('verify',                'Auth',      'Verify',         'Minimal', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'Active'),
('signup',                'User',      'Signup',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 'Active'),
('dashboard',             'Dashboard', 'Dashboard',      '',        1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Active'),
('route',                 'Route',     'Routes',         '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('route/edit',            'Route',     'Route',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('route/notfound',        'Route',     'NotFound',       '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('route/redirect',        'Route',     'Redirect',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/',                 'Menu',      'Menus',          '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/edit',             'Menu',      'Menu',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/item',             'Menu',      'MenuItems',      '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/item/edit',        'Menu',      'MenuItem',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('media/image',           'Media',     'Images',         '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('media/image/edit',      'Media',     'Image',          '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/article',       'Content',   'Articles',       '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/article/edit',  'Content',   'Article',        '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/category',      'Content',   'Categories',     '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/category/edit', 'Content',   'Category',       '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active');


('user',                  'User',      'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Active'),
('user/edit',             'User',      'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 'Active'),
('user/cc',               'User',      'CreditCard',     '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 'Active'),
('user/id',               'User',      'Identification', '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 'Active'),
('user/program',          'User',      'Program',        '',        1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 'Active'),
('content',               'Content',   'Dashboard',        '',      1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Active'),
('content/article',       'Content',   'Article',        '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/blog',          'Content',   'Blog',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/category',      'Content',   'Category',       '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/news',          'Content',   'News',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/tag',           'Content',   'Tag',            '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/edit',          'Content',   'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/media/audio',   'Media',     'Audio',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/media/image',   'Media',     'Image',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/media/video',   'Media',     'Video',          '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/media/edit',    'Media',     'Edit',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/media/info',    'Media',     'Info',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/feed',          'Feed',      'Feed',           '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 'Active'),
('content/feed/channel',  'Feed',      'Channel',        '',        1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 'Active'),

### My Travel

INSERT INTO `route` (`route`, `redirect`) VALUES
('', 'dashboard');

INSERT INTO `route` (`route`, `category`, `component`, `option`, `public`, `template`, `get`, `post`, `email`) VALUES
('login',         'Core', 'Auth', 'Login',           1,'Minimal', 1, 1),
('logout',        'Core', 'Auth', 'Logout',          1,'Minimal', 0),
('forgot',        'Core', 'Auth', 'Forgot',          1,'Minimal', 1, 1, 1),
('verify',        'Core', 'Auth', 'Verify',          1,'Minimal', 1, 1, 1),
('signup',        'User', 'Signup',          1,'Minimal', 0, 1, 1),
('dashboard',     'Dashboard', 'Dashboard',       0,'',        0, 0),
('trip', 'Trip',  'Dashboard',       0,'',        0, 0),
('user', 'User',  'Dashboard',       0,'',        0, 0),
('user/edit',     'User', 'Edit',            0,'',        0, 1),
('user/cc',       'User', 'CreditCard',      0,'',        0, 1),
('user/id',       'User', 'Identification',  0,'',        0, 1),
('user/program',  'User', 'Program',         0,'',        0, 1),
('api/area',      'Location', 'Lookup',          1,'',        1, 0, 0);

### CMS

INSERT INTO `route` (`route`, `category`, `component`, `option`, `public`, `template`, `get`, `post`, `email`) VALUES
('content/article', 'CMS', 'Content', 'Article',  1,'', 1, 0),
('content/blog', 'CMS', 'Content', 'Blog',     1,'', 1, 0),
('content/category', 'CMS', 'Content', 'Category', 1,'', 1, 0),
('content/news', 'CMS', 'Content', 'News',     1,'', 1, 0),
('content/tag', 'CMS', 'Content', 'Tag',      1,'', 1, 0),
('content/edit', 'CMS', 'Content', 'Edit',     1,'', 1, 1),

('content/media/audio', 'CMS', 'Media', 'Audio',      1,'', 1, 0),
('content/media/image', 'CMS', 'Media', 'Image',      1,'', 1, 0),
('content/media/video', 'CMS', 'Media', 'Video',      1,'', 1, 0),
('content/media/edit', 'CMS', 'Media', 'Edit',       1,'', 1, 1),
('content/media/info', 'CMS', 'Media', 'Info',       1,'', 1, 0),

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
