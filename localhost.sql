SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` text NOT NULL,
  `picture` text NOT NULL,
  `author` text NOT NULL,
  `content` text NOT NULL,
  `date` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` text NOT NULL,
  `toUser` text NOT NULL,
  `type` text NOT NULL,
  `picture` text NOT NULL,
  `user` text NOT NULL,
  `comment` text NOT NULL,
  `date` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `mimetype` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` text NOT NULL,
  `username` text NOT NULL,
  `name` text NOT NULL,
  `date` text NOT NULL,
  `role` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;