<?php
/**
 * Cron entry for archiving old threads.
 * 
 * Copyright (c) 2014 XenStop.com
 * You may not redistribute any code contained in this addon.
 */
class XenStop_AdvArchiver_CronEntry_ArchiveThreads
{
	/**
	 * Process.
	 */
	public static function run()
	{
		$model = XenForo_Model::create('XenStop_AdvArchiver_Model_Rule');
		$rules = $model->getEnabledRules();
		foreach($rules as $rule) {
			if ($rule['close'] == 1 && $rule['archive_type'] == 'none')
			{
				$openOnly = true;
			}
			else
			{
				$openOnly = false;
			}
			if ($rule['ignore_sticky'] == 1)
			{
				$ignoreSticky = true;
			}
			else
			{
				$ignoreSticky = false;
			}
			if ($rule['ignore_open'] == 1 && $openOnly == false)
			{
				$ignoreOpen = true;
			}
			else
			{
				$ignoreOpen = false;
			}
			$threads = $model->getThreads($rule['node_id'], $rule['max_age'], $rule['max_age_lastpost'], $openOnly, $ignoreSticky, $ignoreOpen);
			if ($threads)
			{
				foreach($threads as $thread) {
					if ($rule['close'] == 1)
					{
						$threadUpdateData['discussion_open'] = 0;
					}
					switch($rule['archive_type']) {
						case 'archive':
							$threadUpdateData['node_id'] = $rule['archive_node_id'];
							if (!XenForo_Model::create('XenForo_Model_ThreadPrefix')->verifyPrefixIsUsable($thread['prefix_id'], $rule['archive_node_id']))
							{
								$threadUpdateData['prefix_id'] = 0;
							}
							break;
						case 'soft_delete':
							$threadModel = XenForo_Model::create("XenForo_Model_Thread");
							$threadModel->deleteThread($thread['thread_id'], 'soft', array('reason' => 'Automatic Thread Cleanup'));
							break;
						case 'hard_delete':
							$threadModel = XenForo_Model::create("XenForo_Model_Thread");
							$threadModel->deleteThread($thread['thread_id'], 'hard', array('reason' => 'Automatic Thread Cleanup'));
							break;
					}
					if ($rule['archive_type'] == 'soft_delete' || $rule['archive_type'] == 'hard_delete')
					{
						continue;
					}
					$threadWriter = XenForo_DataWriter::create('XenForo_DataWriter_Discussion_Thread');
					$threadWriter->setExistingData($thread['thread_id']);
					$threadWriter->bulkSet($threadUpdateData);
					$threadWriter->save();

					if ($threadWriter->isChanged('node_id') && $rule['archive_create_redirect'] == 1)
					{
						XenForo_Model::create('XenForo_Model_ThreadRedirect')->createRedirectThread(
							XenForo_Link::buildPublicLink('threads', $thread), $thread,
							'thread-'.$thread['thread_id'].'-'.$thread['node_id'].'-',0
						);
					}
				}
			}
		}
		return true;
	}
}