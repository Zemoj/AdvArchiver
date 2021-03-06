<?php
class XenStop_AdvArchiver_ControllerAdmin_Archive extends XenForo_ControllerAdmin_Abstract
{
	public function actionIndex()
	{
		$ruleModel = $this->_getRuleModel();

		$rules = $ruleModel->getRules();

		$viewParams = array(
			'rules' => $rules,
		);

		return $this->responseView('XenStop_AdvArchiver_ViewAdmin_Rule_List', 'XenStop_AdvArchiver_Rule_List', $viewParams);
	}
	
	public function actionSave()
	{
		$this->_assertPostOnly();
		$ruleModel = $this->_getRuleModel();
		$ruleId = $this->_input->filterSingle('rule_id', XenForo_Input::UINT);
		$input = $this->_input->filter(array(
			'title'						=> XenForo_Input::STRING,
			'enabled'					=> XenForo_Input::UINT,
			'node_id'					=> XenForo_Input::UINT,
			'max_age'					=> XenForo_Input::UINT,
			'max_age_lastpost'			=> XenForo_Input::UINT,
			'archive_type'				=> XenForo_Input::STRING,
			'close'						=> XenForo_Input::INT,
			'ignore_sticky'				=> XenForo_Input::INT,
			'ignore_open'				=> XenForo_Input::INT,
			'archive_node_id'			=> XenForo_Input::UINT,
			'archive_create_redirect'	=> XenForo_Input::INT,
		));
		$writer = $this->_getRuleDataWriter();
		$rule = $ruleModel->getRuleById($ruleId);
		if ($rule)
		{
			$writer->setExistingData($ruleId);
		}
		$writer->bulkSet($input);
		$writer->save();
		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('archiverules') . $this->getLastHash($writer->get('rule_id'))
		);
	}
	
	public function actionEdit()
	{
		$nodeModel = $this->_getNodeModel();
		if ($ruleId = $this->_input->filterSingle('rule_id', XenForo_Input::UINT))
		{
			$ruleModel = $this->_getRuleModel();
			$rule = $ruleModel->getRuleById($ruleId);
			if (!$rule)
			{
				return $this->responseError(new XenForo_Phrase('XenStop_AdvArchiver_Rule_Not_Found'), 404);
			}
		}
		else
		{
			$rule = array(
				'enabled'					=> 1,
				'node_id'					=> 0,
				'max_age'					=> 0,
				'max_age_lastpost'			=> 0,
				'archive_time'				=> 'none',
				'close'						=> 0,
				'ignore_sticky'				=> 0,
				'archive_node_id'			=> 0,
				'archive_create_redirect'	=> 0,
			);
		}
		$nodes = $nodeModel->getAllNodes();
		$viewParams = array(
			'rule'			=> $rule,
			'nodes'			=> $nodeModel->getNodeOptionsArray($nodes, $rule['node_id']),
			'archiveNodes'	=> $nodeModel->getNodeOptionsArray($nodes, $rule['archive_node_id']),
		);
		return $this->responseView('XenStop_XenStop_AdvArchiver_ViewAdmin_Rule_Edit', 'XenStop_AdvArchiver_Rule_Edit', $viewParams);
	}
	
	public function actionDelete()
	{
		$ruleId = $this->_input->filterSingle('rule_id', XenForo_Input::UINT);
		$writer = $this->_getRuleDataWriter();
		$writer->setExistingData($ruleId);
		$writer->delete();
		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('archiverules') . $this->getLastHash($writer->get('rule_id')),
			new XenForo_Phrase('deleted'));
	}
	
	public function actionAdd()
	{
		return $this->responseREroute('XenStop_AdvArchiver_ControllerAdmin_Archive', 'edit');
	}
	
	protected function _getRuleModel()
	{
		return XenForo_Model::create('XenStop_AdvArchiver_Model_Rule');
	}
	
	protected function _getRuleDataWriter()
	{
		return XenForo_DataWriter::create('XenStop_AdvArchiver_DataWriter_Rule');
	}
	
	protected function _getNodeModel()
	{
		return XenForo_Model::create('XenForo_Model_Node');
	}
}