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
		if(!$version) {
			self::_getDb()->query("
					CREATE TABLE IF NOT EXISTS `xs_advarchiver_rule` (
					  `node_id` int(10) unsigned NOT NULL,
					  `enabled` tinyint(1) NOT NULL DEFAULT '0',
					  `max_age` int(10) unsigned DEFAULT NULL,
					  `max_age_lastpost` int(10) unsigned DEFAULT NULL,
					  `archive_type` varchar(50) NOT NULL DEFAULT '0',
					  `archive_create_redirect` tinyint(1) NOT NULL DEFAULT '0',
					  `archive_node_id` int(10) unsigned DEFAULT NULL,
					  `close` tinyint(1) NOT NULL DEFAULT '0',
					  `ignore_sticky` tinyint(1) NOT NULL DEFAULT '0',
					  UNIQUE KEY `node_id` (`node_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					");
			return true;
		} else {
			$queries = array();
			if($version < 1010071) {
				$queries[] = "ALTER TABLE  `xs_advarchiver_rule` ADD  `ignore_sticky` TINYINT( 1 ) NOT NULL DEFAULT  '0'";
			}
			
			if(!empty($queries)) {
				foreach($queries as $query) {
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
		if(self::$_db == null) {
			self::$_db = XenForo_Application::get('db');
		}
		return self::$_db;
	}
}