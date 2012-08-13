CREATE TABLE IF NOT EXISTS `ml_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `native_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Language title (native language) default is copied from title',
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

INSERT INTO `ml_language` (`id`, `language`, `title`, `native_title`, `is_active`) VALUES
(1, 'en_US', 'English', 'English', 1);
