CREATE TABLE IF NOT EXISTS `articles` (
	`id` int(11) NOT NULL auto_increment,
	`version` int(4) NOT NULL,
	`title` varchar(128) NOT NULL,
	`slug` varchar(128) NOT NULL,
	`text` text NOT NULL,
	`description` varchar(256) default NULL,
	`keywords` varchar(256) default NULL,
	`date` int(10) NOT NULL,
	`state` varchar(16) NOT NULL,
	`author_id` int(11) NOT NULL,
	`subcategory_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

