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

class SocialGroupAppAudiosHookNotificationLikes extends SocialAppHooks
{
	public function execute(&$item)
	{
		// If the skipExcludeUser is true, we don't unset myself from the list
		$excludeCurrentViewer = (isset($item->skipExcludeUser) && $item->skipExcludeUser) ? false : true;

		$users = $this->getReactionUsers($item->uid, $item->context_type, $item->actor_id, $excludeCurrentViewer);
		$names = $this->getNames($users);

		if (!$users) {
			return;
		}

		$item->reaction = $this->getReactions($item->uid, $item->context_type);

		// Assign first users from likers for avatar
		$item->userOverride = ES::user($users[0]);

		// When user likes on a single audio item
		if ($item->context_type == 'audios.group.create' || $item->context_type == 'audios.group.featured') {
			$audio = ES::audio($item->uid, SOCIAL_TYPE_GROUP, $item->context_ids);

			// Set the audio image
			$item->image = $audio->getAlbumArt();
			$item->content = '';

			// Since context_ids is not referring to the cluster id, we need to retrieve the cluster directly from the audio itself
			$cluster = ES::cluster($audio->table->type, $audio->table->uid);

			// We need to determine if the user is the owner
			if ($audio->user_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
				$item->title = JText::sprintf($this->getPlurality('APP_CLUSTER_AUDIO_NOTIFICATIONS_LIKES_YOUR_AUDIO', $users), $names, $audio->title, $cluster->getTitle());
				return;
			}

			// We do not need to pluralize here since we know there's always only 1 recipient
			if ($item->actor_id == $audio->user_id && count($users) == 1) {
				$actor = ES::user($item->actor_id);
				$item->title = JText::sprintf($this->getGenderLanguage('APP_USER_AUDIO_NOTIFICATIONS_LIKES_USERS_AUDIO', $actor->id), $actor->getName());

				return;
			}

			// For other users, we just post a generic message
			$item->title = JText::sprintf($this->getPlurality('APP_USER_AUDIO_NOTIFICATIONS_LIKES_USERS_AUDIO', $users), $names, ES::user($audio->user_id)->getName());

			return;
		}

		return;
	}

}
