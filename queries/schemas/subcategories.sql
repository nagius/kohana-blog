CREATE TABLE IF NOT EXISTS `subcategories` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(32) NOT NULL,
	`category_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

