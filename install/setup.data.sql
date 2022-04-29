--
-- Table structure `{PREFIX}s_products`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_products`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `published` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-Unpublished|1-Published',
    `availability` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-Not available|1-In stock|2-On order',
    `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-Select|1-Hit|2-New|3-Top|4-Sale|5-Stock',
    `category` int(11) NOT NULL DEFAULT 0,
    `position` int(11) NOT NULL DEFAULT 0,
    `alias` varchar(512) NOT NULL,
    `cover` varchar(512) NOT NULL DEFAULT '',
    `price` float(9,2) NOT NULL DEFAULT '0.00',
    `price_old` float(9,2) NOT NULL DEFAULT '0.00',
    `weight` float(9,2) NOT NULL DEFAULT '0.00',
    `code` varchar(64) NOT NULL DEFAULT '',
    `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-Simple',
    `rating` int(11) NOT NULL DEFAULT 0,
    `views` int(11) NOT NULL DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM {TABLEENCODING} AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure `{PREFIX}s_product_translates`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_product_translates`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product` int(11) NOT NULL,
    `lang` varchar(4) NOT NULL DEFAULT 'base',
    `pagetitle` varchar(512) NOT NULL DEFAULT '',
    `introtext` text NOT NULL,
    `content` text NOT NULL,
    `seotitle` varchar(128) NOT NULL DEFAULT '',
    `seodescription` varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `product_lang` (`product`, `lang`)
) ENGINE = MyISAM {TABLEENCODING} AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure `{PREFIX}s_product_category`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_product_category` (
    `product` int(11) NOT NULL,
    `category` int(11) NOT NULL
) ENGINE=MyISAM {TABLEENCODING};

-- --------------------------------------------------------

--
-- Table structure `{PREFIX}s_product_features`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_product_features`
(
    `fid` int(11) NOT NULL AUTO_INCREMENT,
    `product` int(11) NOT NULL,
    `filter` int(11) NOT NULL,
    `position` int(11) NOT NULL DEFAULT 0,
    `alias` varchar(512) NOT NULL,
    `base` tinytext NOT NULL DEFAULT '',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`fid`),
    KEY (`product`),
    KEY (`filter`),
    KEY (`alias`)
    ) ENGINE = MyISAM {TABLEENCODING} AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure `{PREFIX}s_filters`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_filters`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-Characteristic|1-Filter',
    `type_select` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-Number|1-Text|2-Select|3-Multiselect',
    `position` int(11) NOT NULL DEFAULT 0,
    `alias` varchar(512) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM {TABLEENCODING} AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure `{PREFIX}s_filter_translates`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_filter_translates`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `filter` int(11) NOT NULL,
    `lang` varchar(4) NOT NULL DEFAULT 'base',
    `pagetitle` varchar(512) NOT NULL DEFAULT '',
    `introtext` text NOT NULL,
    `content` text NOT NULL,
    `seotitle` varchar(128) NOT NULL DEFAULT '',
    `seodescription` varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `filter_lang` (`filter`, `lang`)
) ENGINE = MyISAM {TABLEENCODING} AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure `{PREFIX}s_filter_values`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_filter_values`
(
    `vid` int(11) NOT NULL AUTO_INCREMENT,
    `filter` int(11) NOT NULL,
    `position` int(11) NOT NULL DEFAULT 0,
    `alias` varchar(512) NOT NULL,
    `base` tinytext NOT NULL DEFAULT '',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`vid`)
) ENGINE = MyISAM {TABLEENCODING} AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure `{PREFIX}s_filter_category`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_filter_category` (
    `filter` int(11) NOT NULL,
    `category` int(11) NOT NULL
) ENGINE=MyISAM {TABLEENCODING};

-- --------------------------------------------------------

--
-- Create ecommerce structure
--

INSERT INTO `{PREFIX}site_content` (`alias`, `pagetitle`, `published`) SELECT * FROM (SELECT 'catalog' AS `alias`, 'Catalog' AS `pagetitle`, 1 AS `published`) AS temp WHERE NOT EXISTS (SELECT `alias` FROM `{PREFIX}site_content` WHERE `alias` = 'catalog') LIMIT 1;

-- --------------------------------------------------------

--
-- Settings table `{PREFIX}system_settings`
--

REPLACE INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('catalog_root', (SELECT `id` FROM `{PREFIX}site_content` WHERE `alias` = 'catalog'))

-- --------------------------------------------------------