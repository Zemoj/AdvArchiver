<?php
//class XenForo_DataWriter_Forum extends XenForo_DataWriter_Node implements XenForo_DataWriter_DiscussionContainerInterface
class XenStop_AdvArchiver_DataWriter_Forum extends XFCP_XenStop_AdvArchiver_DataWriter_Forum
{
	protected function _getFields()
	{
		return parent::_getFields() + array('xs_advarchiver_rule' => array(
			'node_id'  			 => array('type' => self::TYPE_UINT, 'default' => array('xf_node', 'node_id'), 'required' => false),
			'enabled'  			 => array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'max_age'  			 => array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'max_age_lastpost'   => array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'archive_type'		 => array('type' => self::TYPE_STRING, 'default' => 'none', 'required' => true),
			'close'				 => array('type' => self::TYPE_INT, 'default' => 0, 'required' => true),
			'ignore_sticky'		 => array('type' => self::TYPE_INT, 'default' => 0, 'required' => true),
			'archive_node_id'	 => array('type' => self::TYPE_UINT, 'default' => 0, 'required' => true),
			'archive_create_redirect' => array('type' => self::TYPE_INT, 'default' => 0, 'required' => true),
		));
	}
}