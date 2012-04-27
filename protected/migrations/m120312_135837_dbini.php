<?php

class m120312_135837_dbini extends CDbMigration
{
	public function up()
	{
        $sql="
        CREATE TABLE IF NOT EXISTS `Bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_bookmark_post` (`postId`),
  KEY `FK_bookmark_user` (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
INSERT INTO `Bookmark` (`id`, `postId`, `userId`) VALUES
(1, 5, 1);
CREATE TABLE IF NOT EXISTS `Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;
INSERT INTO `Category` (`id`, `name`, `slug`) VALUES
(1, '加油', ''),
(2, 'hh', 'hh'),
(3, 'cc', 'cc');
CREATE TABLE IF NOT EXISTS `Comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `contentDisplay` text COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL,
  `createTime` int(11) DEFAULT NULL,
  `authorName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `postId` int(11) NOT NULL,
  `authorId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_post` (`postId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
INSERT INTO `Comment` (`id`, `content`, `contentDisplay`, `status`, `createTime`, `authorName`, `email`, `postId`, `authorId`) VALUES
(1, 'I Love ', '<p>I Love</p>\n', 1, 1326050636, 'casa', 'casatwy@msn.com', 13, 1);
CREATE TABLE IF NOT EXISTS `File` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `alt` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `Page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `createTime` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  `authorId` int(11) NOT NULL,
  `authorName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_page_author` (`authorId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `Post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `titleLink` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `contentshort` text COLLATE utf8_unicode_ci NOT NULL,
  `contentbig` text COLLATE utf8_unicode_ci,
  `tags` text COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL,
  `createTime` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  `commentCount` int(11) DEFAULT '0',
  `categoryId` int(11) DEFAULT NULL,
  `authorId` int(11) NOT NULL,
  `authorName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title2` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `content2` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post_author` (`authorId`),
  KEY `FK_post_category` (`categoryId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;
CREATE TABLE IF NOT EXISTS `PostTag` (
  `postId` int(11) NOT NULL,
  `tagId` int(11) NOT NULL,
  PRIMARY KEY (`postId`,`tagId`),
  KEY `FK_tag` (`tagId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO `PostTag` (`postId`, `tagId`) VALUES
(5, 6),
(6, 7),
(7, 8),
(8, 9),
(9, 10),
(10, 11),
(11, 12),
(12, 14),
(13, 13);
CREATE TABLE IF NOT EXISTS `Tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;
INSERT INTO `Tag` (`id`, `name`) VALUES
(1, ''),
(2, ''),
(3, ''),
(4, ''),
(5, ''),
(6, ''),
(7, ''),
(8, ''),
(9, ''),
(10, ''),
(11, ''),
(12, ''),
(13, ''),
(14, '');
CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `banned` int(11) NOT NULL,
  `avatar` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwordLost` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmRegistration` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `about` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;
INSERT INTO `User` (`id`, `username`, `password`, `email`, `url`, `status`, `banned`, `avatar`, `passwordLost`, `confirmRegistration`, `about`) VALUES
(1, 'casa', '6752324b14fe3c3c8df0d973e5ae32ed', 'casatwy@msn.com', '', 0, 0, '4mmn7j1bqu.jpg', NULL, NULL, NULL),
(2, 'ccc', '9df62e693988eb4e1e1444ece0578579', 'cc@126.com', '', 2, 0, 'rmeagb7qve.jpg', NULL, NULL, NULL),
(3, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'chenxin448@126.com', '', 2, 0, '9gnts1yg1h.jpg', NULL, NULL, NULL);


";


	}

	public function down()
	{
		echo "m120312_135837_dbini does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/ to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
