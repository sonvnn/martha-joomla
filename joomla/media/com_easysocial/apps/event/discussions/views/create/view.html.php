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

class DiscussionsViewCreate extends SocialAppsView
{
	public function display($uid = null, $docType = null)
	{
		ES::requireLogin();

		$event = FD::event($uid);

		// Check if the user is a member of the group
		if (!$event->getGuest()->isGuest() && !$this->my->isSiteAdmin()) {
			FD::info()->set(false, JText::_('COM_EASYSOCIAL_EVENTS_ONLY_GUEST_ARE_ALLOWED'), SOCIAL_MSG_ERROR);
			return $this->redirect($event->getPermalink(false));
		}

		$this->page->title('APP_GROUP_DISCUSSIONS_CREATE_SUBTITLE');
		$this->setTitle('APP_GROUP_DISCUSSIONS_CREATE_SUBTITLE');

		// Get the discussion item
		$discussion = FD::table('Discussion');

		// Determines if we should allow file sharing
		$access = $event->getAccess();
		$files  = $access->get('files.enabled', true);

		$params = $this->getParams();
		$editor = $params->get('editor', 'bbcode');

		$this->set('files', $files);
		$this->set('discussion', $discussion);
		$this->set('cluster', $event);
		$this->set('editor', $editor);

		echo parent::display('themes:/site/discussions/form/default');
	}
}
