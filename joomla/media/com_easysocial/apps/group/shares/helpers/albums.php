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

require_once(dirname(__FILE__) . '/abstract.php');

class SocialGroupSharesHelperAlbums extends SocialGroupSharesHelper
{
	/**
	 * Gets the content of the repost
	 *
	 * @since   2.0
	 * @access  public
	 * @param   string
	 * @return
	 */
	public function getContent()
	{
		$message = $this->formatContent($this->share->content);
		
		$album = $this->getSource();

		// Get user's privacy.
		$privacy = $this->my->getPrivacy();

		if (!$privacy->validate('albums.view', $album->id, SOCIAL_TYPE_ALBUM, $album->uid)) {
			return false;
		}

		$theme = ES::themes();
		$theme->set('album', $album);
		$theme->set('message', $message);

		$html = $theme->output('themes:/site/streams/repost/albums/preview');

		return $html;
	}

	/**
	 * Generates the unique link id for the original reposted item
	 *
	 * @since   2.0
	 * @access  public
	 * @param   string
	 * @return
	 */
	public function getLink($sef = true)
	{
		$link = ESR::albums(array('id' => $this->item->contextId, 'sef' => $sef));

		return $link;
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
			$album = ES::table('Album');
			$album->load($this->share->uid);

			$items[$this->share->uid] = $album;
		}

		return $items[$this->share->uid];
	}

	public function getStreamTitle()
	{
		$names = ES::string()->namesToStream($this->item->actors, true, 3);

		// Load the album
		$album = $this->getSource();
		$creator = ES::user($album->user_id);

		$theme = ES::themes();
		$theme->set('names', $names);
		$theme->set('album', $album);
		$theme->set('creator', $creator);

		$html = $theme->output('themes:/site/streams/repost/albums/title');

		return $html;
	}
}
