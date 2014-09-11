<?php
class XenStop_AdvArchiver_Listener_CodeEvents
{
	public static function loadClass($class, array &$extend)
	{
		switch($class) {
			case 'XenForo_ControllerAdmin_Forum':
				$extend[] = 'XenStop_AdvArchiver_ControllerAdmin_Forum';
				break;
		}
	}
}