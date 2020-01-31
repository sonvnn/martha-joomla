<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class SocialEventAppStoryHookNotificationUpdates
{
	/**
	 * Processes comment notifications
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function execute(SocialTableNotification &$item)
	{
		// Get the event item
		$event = ES::event($item->context_ids);

		// Get the actor
		$actor = ES::user($item->actor_id);

		// Format the title
		if ($item->context_type == 'story.event.create') {
			$item->title = JText::sprintf('APP_EVENT_STORY_USER_POSTED_IN_EVENT', $actor->getName(), $event->getName());
			$item->image = $event->getAvatar();

			$content = '';

			if (!empty($item->content)) {
				$content = ES::string()->processEmoWithTruncate($item->content);
			}

			$item->content = $content;

			return $item;
		}

		if ($item->context_type == 'links.event.create') {

			$model = ES::model( 'Stream' );
			$links = $model->getAssets($item->uid, SOCIAL_TYPE_LINKS);

			if (!$links) {
				return;
			}

			$link = ES::makeObject($links[0]->data);

			// Retrieve the image cache path
			$stream = ES::stream();
			$streamItem = $stream->getItem($item->uid);

			if (!$streamItem) {
				$item->exclude = true;
				return;
			}

			$streamItem = $streamItem[0];

			if (!$streamItem) {
				return;
			}

			$assets = $streamItem->getAssets();

			if ($assets) {
				$assets = $assets[0];
			}

			$app = ES::table('App');
			$app->load(array('element' => 'links', 'group' => SOCIAL_TYPE_EVENT));

			$params = $app->getParams();

			$image = ES::links()->getImageLink($assets, $params);

			$item->image = $image;
			$item->content = '';
			$item->title = JText::sprintf('APP_EVENT_STORY_USER_SHARED_LINK_IN_EVENT', $actor->getName(), $event->getName(), $link->title);
		}

		// Someone shared a file in a event
		if ($item->context_type == 'file.event.uploaded') {

			// Get the file object
			$file = ES::table('File');
			$file->load($item->context_ids);

			$event = ES::event($item->uid);

			$item->title = JText::sprintf('APP_EVENT_STORY_USER_SHARED_FILE_IN_EVENT', $actor->getName(), $event->getName());
			$item->content = $file->name;

			if ($file->hasPreview()) {
				$item->image = $file->getPreviewURI();
			}

			return;
		}


		// Someone shared a photo in a event
		if ($item->context_type == 'photos.event.share') {

			// Based on the stream id, we need to get the stream item id.
			$stream = ES::table('Stream');
			$stream->load($item->uid);

			// Get child items
			$streamItems = $stream->getItems();

			// Since we got all the child of stream, we can get the correct count
			$count = count($streamItems);

			if ($count && $count == 1) {

				$photo = ES::table('Photo');
				$photo->load($streamItems[0]->id);

				$item->title = JText::sprintf('APP_EVENT_STORY_USER_SHARED_SINGLE_PHOTO_IN_EVENT', $actor->getName(), $event->getName());
				$item->image = $photo->getSource();
				$item->content = '';

				return;
			}

			$item->title = JText::sprintf('APP_EVENT_STORY_USER_SHARED_MULTIPLE_PHOTOS_IN_EVENT', $actor->getName(), $count, $event->getName());
			$item->content = '';

			return;
		}

		return $item;
	}
}
