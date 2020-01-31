<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class DiscussionsViewEdit extends SocialAppsView
{
	public function display($uid = null , $docType = null)
	{
		ES::requireLogin();

		$group = ES::group($uid);

		$this->page->title('APP_GROUP_DISCUSSIONS_EDITING_SUBTITLE');
		$this->setTitle('APP_GROUP_DISCUSSIONS_EDITING_SUBTITLE');

		// Get the discussion item
		$discussion = FD::table('Discussion');
		$discussion->load(JRequest::getInt('discussionId'));

		if ($discussion->created_by != $this->my->id && !$group->isAdmin() && !$this->my->isSiteAdmin()) {
			FD::info()->set(false, JText::_('COM_EASYSOCIAL_GROUPS_ONLY_MEMBER_ARE_ALLOWED'), SOCIAL_MSG_ERROR);
			return $this->redirect($group->getPermalink(false));
		}

		// Determines if we should allow file sharing
		$access = $group->getAccess();
		$files = $access->get('files.enabled' , true);

		$params = $this->getParams();
		$editor = $params->get('editor', 'bbcode');

		$this->set('files', $files);
		$this->set('discussion', $discussion);
		$this->set('cluster', $group);
		$this->set('editor', $editor);

		echo parent::display('themes:/site/discussions/form/default');
	}
}
