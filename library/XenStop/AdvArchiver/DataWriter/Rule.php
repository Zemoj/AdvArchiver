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
			'node_id'  					=> array('type' => self::TYPE_UINT, 'default' => 0, 'required' => false),
			'title'						=> array('type' => self::TYPE_STRING, 'default' => NULL, 'required' => false),
			'enabled'  					=> array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'max_age'  					=> array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'max_age_lastpost'		  	=> array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'archive_type'				=> array('type' => self::TYPE_STRING, 'default' => 'none', 'required' => true),
			'close'						=> array('type' => self::TYPE_INT, 'default' => 0, 'required' => true),
			'ignore_sticky'				=> array('type' => self::TYPE_INT, 'default' => 0, 'required' => true),
			'archive_node_id'			=> array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'archive_create_redirect'	=> array('type' => self::TYPE_INT, 'default' => 0, 'required' => true),
		));
	}
	
	protected function _getRuleModel()
	{
		return XenForo_Model::create('XenStop_AdvArchiver_Model_Rule');
	}
	
	protected function _getExistingData($data)
	{
		$nodeId = $data;
		
		return array('xs_advarchiver_rule' => $this->_getRuleModel()->getRuleByNodeId($nodeId));
	}
	
	protected function _getUpdateCondition($tableName)
	{
		return 'node_id = ' . $this->_db->quote($this->getExisting('node_id'));
	}
}