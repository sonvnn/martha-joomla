<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(__DIR__ . '/abstract.php');

class SocialSharesHelperPhotos extends SocialSharesHelper
{
	/**
	 * Gets the content of the repost
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getContent()
	{
		$message = $this->formatContent($this->share->content);

		$photoMessage = '';


		$params = ES::registry($this->share->params);
		$streamId = $params->get('streamId', '');

		if ($streamId) {
			$tbl = ES::table('stream');
			$tbl->load($streamId);

			$photoMessage = $this->formatContent($tbl->content);
		}

		// Load the photo object
		$photo = $this->getSource();

		// Determines if the current user is allowed to view
		$privacy = $this->my->getPrivacy();

		if (!$privacy->validate('photos.view', $photo->id, SOCIAL_TYPE_PHOTO, $photo->user_id)) {
			return $this->restricted();
		}

		$theme = ES::themes();
		$theme->set('photo', $photo);
		$theme->set('message', $message);
		$theme->set('photoMessage', $photoMessage);

		$preview = $theme->output('themes:/site/streams/repost/photos/preview');

		return $preview;
	}

	/**
	 * Gets the repost source message
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getSource()
	{
		static $items = array();

		if (!isset($items[$this->share->uid])) {
			$photo = ES::table('Photo');
			$photo->load($this->share->uid);

			$items[$this->share->uid] = $photo;
		}

		return $items[$this->share->uid];
	}

	/**
	 * Generates the unique link id for the original reposted item
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getLink($sef = true)
	{
		$link = ESR::photos(array('id' => $this->item->contextId, 'sef' => $sef));

		return $link;
	}

	/**
	 * Get the stream title
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getStreamTitle()
	{
		// Load the album
		$photo = $this->getSource();
		$creator = ES::user($photo->user_id);

		// Since it may be aggregated
		$names = ES::string()->namesToStream($this->item->actors, true, 3);

		$theme = ES::themes();
		$theme->set('names', $names);
		$theme->set('photo', $photo);
		$theme->set('creator', $creator);

		$title = $theme->output('themes:/site/streams/repost/photos/title');

		return $title;
	}
}
