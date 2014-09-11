<?php
class XenStop_AdvArchiver_ControllerAdmin_Forum extends XFCP_XenStop_AdvArchiver_ControllerAdmin_Forum
{
	
	public function actionSave()
	{
		$return = parent::actionSave();
		$nodeId = $this->_input->filterSingle('node_id', XenForo_Input::UINT);
		$ruleModel = $this->_getRuleModel();
		$writerData = $this->_input->filter(array(
			'enabled' => XenForo_Input::UINT,
			'max_age' => XenForo_Input::UINT,
			'max_age_lastpost' => XenForo_Input::UINT,
			'archive_type' => XenForo_Input::STRING,
			'archive_create_redirect' => XenForo_Input::INT,
			'archive_node_id' => XenForo_Input::UINT,
			'close' => XenForo_Input::INT,
			'ignore_sticky' => XenForo_Input::INT,
		));
		$writer = $this->_getRuleDataWriter();
		if($nodeId == 0) {
			$nodeId = str_ireplace('admin.php?nodes/#_', '', $return->redirectTarget);
		}
		$rule = $ruleModel->getRuleByNodeId($nodeId);
		if($rule) {
			$writer->setExistingData($nodeId);
		} else {
			$writer->set('node_id', $nodeId);
		}
		$writer->bulkSet($writerData);
		$writer->save();
		return $return;
	}
}