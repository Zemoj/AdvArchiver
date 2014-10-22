<?php
/**
 * Cron entry for archiving old threads.
 * 
 * Copyright (c) 2014 XenStop.com
 * You may not redistribute any code contained in this addon.
 */

class XenStop_AdvArchiver_DataWriter_Rule extends XenForo_DataWriter
{
	protected function _getFields()
	{
		return array('xs_advarchiver_rule' => array(
			'rule_id'					=> array('type' => self::TYPE_UINT, 'autoIncrement' => true),
			'node_id'  					=> array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true,
												 'verification' => array('$this', '_verifyNode')),
			'title'						=> array('type' => self::TYPE_STRING, 'default' => NULL, 'required' => true),
			'enabled'  					=> array('type' => self::TYPE_UINT, 'default' => 0),
			'max_age'  					=> array('type' => self::TYPE_UINT, 'default' => 0),
			'max_age_lastpost'		  	=> array('type' => self::TYPE_UINT, 'default' => 0),
			'archive_type'				=> array('type' => self::TYPE_STRING, 'default' => 'none', 'required' => true),
			'close'						=> array('type' => self::TYPE_INT, 'default' => 0),
			'ignore_sticky'				=> array('type' => self::TYPE_INT, 'default' => 0),
			'ignore_open'				=> array('type' => self::TYPE_INT, 'default' => 0),
			'archive_node_id'			=> array('type' => self::TYPE_UINT, 'default' => 0, 'verification' => array('$this', '_verifyArchiveNode')),
			'archive_create_redirect'	=> array('type' => self::TYPE_INT, 'default' => 0),
		));
	}

	protected function _verifyNode(&$nodeId)
	{
		$node = $this->_getNodeModel()->getNodeById($nodeId);

		if ($node['node_type_id'] != 'Forum')
		{
			$this->error(new XenForo_Phrase('XenStop_AdvArchiver_Must_Select_Forum'), 'node_id');
			return false;
		}
	}

	protected function _verifyArchiveNode(&$nodeId)
	{
		if ($nodeId)
		{
			$node = $this->_getNodeModel()->getNodeById($nodeId);

			if ($node['node_type_id'] != 'Forum')
			{
				$this->error(new XenForo_Phrase('XenStop_AdvArchiver_Must_Select_Forum'), 'archive_node_id');
				return false;
			}
		}
	}

	protected function _verifyNodeIsForum(&$nodeId)
	{
		$node = $this->_getNodeModel()->getNodeById($nodeId);

		if ($node['node_type_id'] != 'Forum')
		{
			$this->error(new XenForo_Phrase('XenStop_AdvArchiver_Must_Select_Forum'));
			return false;
		}
	}
	
	protected function _getRuleModel()
	{
		return XenForo_Model::create('XenStop_AdvArchiver_Model_Rule');
	}

	protected function _getNodeModel()
	{
		return XenForo_Model::create('XenForo_Model_Node');
	}
	
	protected function _getExistingData($ruleId)
	{		
		return array('xs_advarchiver_rule' => $this->_getRuleModel()->getRuleById($ruleId));
	}
	
	protected function _getUpdateCondition($tableName)
	{
		return 'rule_id = ' . $this->_db->quote($this->getExisting('rule_id'));
	}
}