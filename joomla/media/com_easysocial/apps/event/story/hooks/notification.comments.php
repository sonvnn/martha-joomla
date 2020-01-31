<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class SocialEventAppStoryHookNotificationComments
{
	/**
	 * Processes comment notifications
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function execute(SocialTableNotification &$item)
	{
		// Get the owner of the stream item since we need to notify the person
		$stream = ES::table('Stream');
		$stream->load($item->uid);

		// Get the event from the stream
		$event = ES::event($stream->cluster_id);

		// Get comment participants
		$model = ES::model('Comments');
		$users = $model->getParticipants($item->uid, $item->context_type);

		// Include the actor of the stream item as the recipient
		$users = array_merge($users, array($item->actor_id));

		// Ensure that the values are unique
		$users = array_unique($users);
		$users = array_values($users);

		// Exclude myself from the list of users.
		$index = array_search(ES::user()->id, $users);

		// If the skipExcludeUser is true, we don't unset myself from the list
		if (isset($item->skipExcludeUser) && $item->skipExcludeUser) {
			$index = false;
		}

		if ($index !== false) {
			unset($users[$index]);
			$users = array_values($users);
		}

		// By default content is always empty;
		$content = '';

		// Only show the content when there is only 1 item
		if (count($users) == 1 && !empty($item->content)) {
			$content = ES::string()->processEmoWithTruncate($item->content);
		}

		// Set the content to the stream
		$item->content = $content;

		// Convert the names to stream-ish
		$names = ES::string()->namesToNotifications($users);

		if ($item->context_type == 'photos.event.share') {

			$this->notificationPhotos($names, $users, $item);
			return;
		}

		// When someone comments on your status update in a event.
		if ($item->context_type == 'story.event.create') {
			// We need to generate the notification message differently for the author of the item and the recipients of the item.
			if ($stream->actor_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
				$item->title = JText::sprintf(ES::string()->computeNoun('APP_EVENT_STORY_USER_POSTED_COMMENT_ON_YOUR_POST', count($users)), $names, $event->getName());

				return $item;
			}

			// This is for 3rd party viewers
			$item->title = JText::sprintf(ES::string()->computeNoun('APP_EVENT_STORY_USER_POSTED_COMMENT_ON_USERS_POST', count($users)), $names, ES::user($stream->actor_id)->getName(), $event->getName());

			return $item;
		}

		// When someone comments on the link you shared in the event
		if ($item->context_type == 'links.event.create') {

			// Get the stream object
			$stream = ES::table('Stream');
			$stream->load($item->uid);

			// Get the event object
			$event = ES::event($stream->cluster_id);

			// Get the link object
			$model = ES::model('Stream');
			$links = $model->getAssets($item->uid, SOCIAL_TYPE_LINKS);

			if (!isset($links[0])) {
				return;
			}

			$link = ES::makeObject($links[0]->data);
			$assets = ES::registry($link);

			// Load the link object
			$linkTbl = ES::table('Link');
			$linkTbl->loadByLink($assets->get('link'));

			// Retrieve the link image
			$image = $linkTbl->getImage($assets);
			$item->image = $image;

			// We need to generate the notification message differently for the author of the item and the recipients of the item.
			if ($stream->actor_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
				$item->title = JText::sprintf(ES::string()->computeNoun('APP_EVENT_STORY_USER_COMMENTED_ON_YOUR_LINK', count($users)), $names, $event->getName(), $link->title);

				return $item;
			}

			// This is for 3rd party viewers
			$item->title = JText::sprintf(ES::string()->computeNoun('APP_EVENT_STORY_USER_COMMENTED_ON_USERS_LINK', count($users)), $names, ES::user($stream->actor_id)->getName(), $event->getName());

			return;
		}
	}

	private function notificationPhotos($names, $users, &$item)
	{
		// Get the stream object
		$stream = ES::table('Stream');
		$stream->load($item->uid);

		// Get the event
		$event = ES::event($item->context_ids);

		// Get all child stream items
		$streamItems = $stream->getItems();

		// Get the first photo since we can't get all photos
		if ($streamItems && isset($streamItems[0])) {

			$streamItem = $streamItems[0];

			$photo = ES::table('Photo');
			$photo->load($streamItem->context_id);

			$item->image = $photo->getSource();
		}

		// We need to generate the notification message differently for the author of the item and the recipients of the item.
		if ($stream->actor_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
			$item->title = JText::sprintf(ES::string()->computeNoun('APP_EVENT_STORY_USER_COMMENTED_ON_YOUR_SHARED_PHOTO', count($users)), $names, $event->getName());

			return $item;
		}

		// This is for 3rd party viewers
		$item->title = JText::sprintf(ES::string()->computeNoun('APP_EVENT_STORY_USER_COMMENTED_ON_USERS_SHARED_PHOTO', count($users)), $names, ES::user($stream->actor_id)->getName(), $event->getName());
	}
}
