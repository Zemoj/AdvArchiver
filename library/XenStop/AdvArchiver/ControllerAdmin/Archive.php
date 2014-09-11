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
	
	public function actionEdit()
	{
		if ($ruleId = $this->_input->filterSingle('rule_id', XenForo_Input::UINT)) {
			$ruleModel = $this->_getRuleModel();
			$rule = $ruleModel->getRuleById($ruleId);
			if(!$rule) {
				return $this->responseError(new XenForo_Phrase('XenStop_AdvArchiver_Rule_Not_Found'), 404);
			}
			$viewParams = array(
				'rule'	=> $rule,
			);
		} else {
			$viewParams = array();
		}
		return $this->responseView('XenStop_XenStop_AdvArchiver_ViewAdmin_Rule_Edit', 'XenStop_AdvArchiver_Rule_Edit', $viewParams);
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
}