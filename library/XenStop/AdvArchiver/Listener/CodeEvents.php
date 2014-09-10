<?php
class XenStop_AdvArchiver_Listener_CodeEvents
{
	public static function load_class($class, array &$extend)
	{
		switch($class) {
			case 'XenForo_ControllerAdmin_Forum':
				$extend[] = 'XenStop_AdvArchiver_ControllerAdmin_Forum';
				break;
			case 'XenForo_DataWriter_Forum':
				$extend[] = 'XenStop_AdvArchiver_DataWriter_Forum';
				break;
		}
	}
	public static function template_hook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
	{
		switch($hookName) {
			case 'admin_forum_edit_tabs':
				$contents .= $template->create('XenStop_AdvArchiver_forum_edit_tab')->render();
				break;
			case 'admin_forum_edit_panes':
				$params['rule'] = XenForo_Application::get('XenStop_AdvArchiver_Params');
				$contents .= $template->create('XenStop_AdvArchiver_forum_edit_fields', $params)->render();
				break;
		}
	}
}