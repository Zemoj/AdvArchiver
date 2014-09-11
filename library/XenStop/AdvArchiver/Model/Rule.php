<?php
/**
 * Advanced Archiver Rule Model
 * 
 * Copyright (c) 2014 XenStop.com
 * You may not redistribute any code contained in this addon.
 */
class XenStop_AdvArchiver_Model_Rule extends XenForo_Model
{
	/**
	 * Get last node ID
	 */
	public function getLastNodeId()
	{
		return $this->_getDb()->lastInsertId('xf_node','node_id');
	}
	/**
	 * Get all automatic archive rules
	 * 
	 * @return array Array of all applicable archive rules.
	 */
	public function getEnabledRules()
	{
		return $this->fetchAllKeyed("SELECT * FROM `xs_advarchiver_rule` WHERE `enabled`=1", 'node_id');
	}
	
	/**
	 * Get all threads for specified node that are older than specified date.
	 * 
	 * @param number Node ID
	 * @param number Number of days since thread was made
	 * @param number Number of days since last post in thread
	 * @return mixed Array of applicable threads
	 */
	public function getThreads($nodeId, $postDate=0, $lastPostDate=0, $openOnly=false, $ignoreSticky, $limit=100)
	{
		$conditionals = "WHERE `node_id` = ".intval($nodeId);
		
		if($postDate != 0) {
			$conditionals .= " AND `post_date` <= ".intval(XenForo_Application::$time - ($postDate * 86400));
		} elseif($lastPostDate != 0) {
			$conditionals .= " AND `last_post_date` <= ".intval(XenForo_Application::$time - ($lastPostDate * 86400));
		} else {
			return false;
		}
		if($openOnly) {
			$conditionals .= " AND `discussion_open` != 0";
		}
		if($ignoreSticky) {
			$conditionals .= " AND `sticky` = 0";
		}
		return $this->fetchAllKeyed("
				SELECT * from `xf_thread`
				".$conditionals."
				LIMIT ".$limit."
				",'thread_id');
	}
	
	public function getRules()
	{
		return $this->fetchAllKeyed("
				SELECT rule.*, node.title AS node_title
				FROM `xs_advarchiver_rule` AS rule
				LEFT JOIN `xf_node` AS node ON (node.node_id = rule.node_id)
				ORDER BY `node_id` ASC
			", 'rule_id');
	}
	
	/**
	 * Get rule information by rule id
	 * @param number ID of the rule
	 */
	public function GetRuleById($ruleId)
	{
		return $this->_getDb()->fetchRow("
				SELECT rule.*, node.title AS node_title
				FROM `xs_advarchiver_rule` AS rule
				LEFT JOIN `xf_node` AS node ON (node.node_id = rule.node_id)
				WHERE `rule_id`=?
			",$ruleId);
	}
}