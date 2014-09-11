<?php

/**
 * Route prefix handler for nodes in the admin control panel.
 *
 * @package XenForo_Nodes
 */
class XenStop_AdvArchiver_Route_PrefixAdmin_ArchiveRule implements XenForo_Route_Interface
{
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		$action = $router->resolveActionWithIntegerParam($routePath, $request, 'rule_id');
		return $router->getRouteMatch('XenStop_AdvArchiver_ControllerAdmin_Archive', $action, 'xsarchiver_rules');
	}

	/**
	 * Method to build a link to the specified page/action with the provided
	 * data and params.
	 *
	 * @see XenForo_Route_BuilderInterface
	 */
	public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams)
	{
		return XenForo_Link::buildBasicLinkWithIntegerParam($outputPrefix, $action, $extension, $data, 'rule_id', 'title');
	}
}