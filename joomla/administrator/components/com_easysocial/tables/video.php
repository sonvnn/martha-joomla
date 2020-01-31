<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

ES::import('admin:/tables/table');
ES::import('admin:/includes/indexer/indexer');

class SocialTableVideo extends SocialTable implements ISocialIndexerTable
{
	public $id = null;
	public $title = null;
	public $description = null;
	public $user_id = null;
	public $uid = null;
	public $type = null;
	public $created = null;
	public $state = null;
	public $isnew = null;
	public $featured = null;
	public $category_id = null;
	public $hits = null;
	public $duration = null;
	public $size = null;
	public $params = null;
	public $storage = 'joomla';
	public $path = null;
	public $original = null;
	public $file_title = null;
	public $source = 'link';
	public $thumbnail = null;
	public $post_as = null;

	/**
	 * Use for photo privacy access
	 */
	public $access = null;
	public $custom_access = null;
	public $field_access = null;
	public $chk_access = null;

	public function __construct($db)
	{
		parent::__construct('#__social_videos', 'id', $db);
	}

	public function syncIndex()
	{
	}

	public function deleteIndex()
	{
	}

	/**
	 * Override store method
	 *
	 * @since   3.1
	 * @access  public
	 */
	public function store($updateNulls = false)
	{
		// always set the chk_access to 1
		// so that new photos created after 3.1 
		// will not need to re-run the
		// privacy access migration.
		$this->chk_access = 1;

		return parent::store();
	}

	/**
	 * Determines if this video is an upload source
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isUpload()
	{
		return $this->source == SOCIAL_VIDEO_UPLOAD;
	}

	/**
	 * Determines if this video is a link source
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isLink()
	{
		return $this->source == SOCIAL_VIDEO_LINK;
	}

	/**
	 * Constructs the alias for this photo
	 *
	 * @since   1.0
	 * @access  public
	 * @param   string
	 * @return
	 */
	public function getAlias($withId = true)
	{
		$title = $this->title;
		$alias = JFilterOutput::stringURLSafe($title);
		if (!$alias) {
			$alias = JFilterOutput::stringURLUnicodeSlug($title);
		}

		if ($withId) {
			$alias = $this->id . ':' . $alias;
		}

		return $alias;
	}

	/**
	 * Method to update the cached sef alias when there
	 * is changes on the alias column
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function updateAliasSEFCache()
	{
		$old = ES::table('Video');
		$old->load($this->id);

		$oldAlias = $old->getAlias();
		$newAlias = $this->getAlias();

		if ($oldAlias != $newAlias) {
			ESR::updateSEFCache($this, $oldAlias, $newAlias);
		}
	}

	/**
	 * Method to delete the cached sef alias when item being removed.
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function deleteSEFCache()
	{
		$alias = $this->getAlias();
		$state = ESR::deleteSEFCache($this, $alias);

		return $state;
	}

	/**
	 * Retrieves the permalink of a video
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function getPermalink($xhtml = true, $uid = null, $utype = null, $from = false, $external = false, $sef = true, $adminSef = false)
	{
		$options = array('id' => $this->getAlias(), 'layout' => 'item', 'external' => $external, 'sef' => $sef, 'adminSef' => $adminSef);

		if ($this->uid && $this->type && $this->type != SOCIAL_TYPE_USER) {
			$cluster = ES::cluster($this->type, $this->uid);

			$options['uid'] = $cluster->getAlias();
			$options['type'] = $this->type;

		} else if ($uid && $utype) {

			if (is_numeric($uid) && $utype == SOCIAL_TYPE_USER) {
				$user = ES::user($this->uid);
				$uid = $user->getAlias();
			}

			$options['uid'] = $uid;
			$options['type'] = $utype;

		} else if ($this->type == SOCIAL_TYPE_USER) {
			$user = ES::user($this->uid);
			$options['uid'] = $user->getAlias();
			$options['type'] = $this->type;
		}

		if ($from !== false && $from) {
			$options['from'] = $from;
		}

		$url = ESR::videos($options, $xhtml);

		return $url;
	}

	/**
	 * Retrieves the external permalink of a video
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function getExternalPermalink($format = null)
	{
		$options = array('id' => $this->getAlias(), 'layout' => 'item', 'external' => true);

		if ($format) {
			$options['format'] = $format;
		}

		if ($this->uid && $this->type && $this->type != SOCIAL_TYPE_USER) {
			$cluster = ES::cluster($this->type, $this->uid);

			$options['uid'] = $cluster->getAlias();
			$options['type'] = $this->type;

		} else if ($this->type == SOCIAL_TYPE_USER) {
			$user = ES::user($this->uid);
			$options['uid'] = $user->getAlias();
			$options['type'] = $this->type;
		}

		$url = ESR::videos($options, false);

		return $url;
	}

	/**
	 * Allows caller to set the duration
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function setDuration(SocialVideoDuration $duration)
	{
		$this->duration = $duration->raw();

		// Save the video object
		return $this->store();
	}

	/**
	 * Allows the caller to set the state to processing
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function processing()
	{
		$this->state = SOCIAL_VIDEO_PROCESSING;

		return $this->store();
	}


	/**
	 * Determines if the video item is unfeatured
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function isUnfeatured()
	{
		return !$this->isFeatured();
	}

	/**
	 * Determines if the video item is featured
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function isFeatured()
	{
		return $this->featured == SOCIAL_VIDEO_PUBLISHED;
	}
}
