--
-- Table structure `{PREFIX}s_products`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}s_products`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-Unpublished|1-Published',
    `availability` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-Unpublished|1-Published',
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `category` int(11) NOT NULL DEFAULT '0',
    `position` int(11) NOT NULL DEFAULT '0',
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
    `seotitle` varchar(128),
    `seodescription` varchar(255),
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `product_lang` (`product`, `lang`)
) ENGINE = MyISAM {TABLEENCODING} AUTO_INCREMENT=1;

-- --------------------------------------------------------