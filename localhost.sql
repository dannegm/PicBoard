SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `pics`;
CREATE TABLE `pics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` text NOT NULL,
  `path` text NOT NULL,
  `content` longtext NOT NULL,
  `md5` text NOT NULL,
  `date` text NOT NULL,
  `prints` text NOT NULL,
  `author` text NOT NULL,
  `status` text NOT NULL,
  `width` text NOT NULL,
  `height` text NOT NULL,
  `mimetype` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` text NOT NULL,
  `username` text NOT NULL,
  `name` text NOT NULL,
  `date` text NOT NULL,
  `role` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;