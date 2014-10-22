<?php
/**
 * Installation class
 * 
 * Copyright (c) 2014 XenStop.com
 * You may not redistribute any code contained in this addon.
 */
class XenStop_AdvArchiver_Listener_Install
{
	protected static $_db;
	public static function install($existingAddOn, $addOnData)
	{
		$version = is_array($existingAddOn)?$existingAddOn['version_id']:NULL;
		if (!$version)
		{
			self::_getDb()->query("
					CREATE TABLE IF NOT EXISTS `xs_advarchiver_rule` (
					  `rule_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `node_id` int(10) unsigned NOT NULL,
					  `title` varchar(50) DEFAULT NULL,
					  `enabled` tinyint(1) NOT NULL DEFAULT '0',
					  `max_age` int(10) unsigned NOT NULL DEFAULT '0',
					  `max_age_lastpost` int(10) unsigned NOT NULL DEFAULT '0',
					  `archive_type` varchar(50) NOT NULL DEFAULT 'none',
					  `archive_create_redirect` tinyint(1) NOT NULL DEFAULT '0',
					  `archive_node_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `close` tinyint(1) NOT NULL DEFAULT '0',
					  `ignore_sticky` tinyint(1) NOT NULL DEFAULT '0',
					  `ignore_open` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`rule_id`),
					  UNIQUE KEY `node_id` (`node_id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
					");
			return true;
		}
		else
		{
			$queries = array();
			if ($version < 1010070)
			{
				$queries[] = "ALTER TABLE  `xs_advarchiver_rule` ADD  `ignore_sticky` TINYINT( 1 ) NOT NULL DEFAULT  '0'";
			}
			if ($version < 1010170)
			{
				$queries[] = "ALTER TABLE `xs_advarchiver_rule`
					CHANGE `max_age` `max_age` INT(10) UNSIGNED NOT NULL DEFAULT '0',
					CHANGE `max_age_lastpost` `max_age_lastpost` INT(10) UNSIGNED NOT NULL DEFAULT '0',
					CHANGE `archive_node_id` `archive_node_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
					CHANGE `archive_type` `archive_type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'none',
					ADD `rule_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
					ADD PRIMARY KEY (`rule_id`),
					ADD `title` VARCHAR(50) NULL DEFAULT NULL AFTER `node_id`,
					ADD `ignore_open` TINYINT(1) NOT NULL DEFAULT '0'";
			}
			
			if (!empty($queries))
			{
				foreach ($queries as $query)
				{
					self::_getDb()->query($query);
				}
			}
		}
	}
	public static function uninstall()
	{
		self::_getDb()->query("DROP TABLE `xs_advarchiver_rule`;");
		return true;
	}
	
	protected static function _getDb()
	{
		if (self::$_db == null)
		{
			self::$_db = XenForo_Application::get('db');
		}
		return self::$_db;
	}
}