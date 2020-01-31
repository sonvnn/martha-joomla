<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Import SocialTable.
FD::import( 'admin:/tables/table' );

/**
 * Object mapping for Broadcast.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.3
 */
class SocialTableBroadcast extends SocialTable
{
	/**
	 * The unique id which is auto incremented.
	 * @var int
	 */
	public $id = null;

	/**
	 * The target id that should receive the broadcast
	 * @var int
	 */
	public $target_id = null;

	/**
	 * The target type that should receive the broadcast
	 * @var string
	 */
	public $target_type = null;

	/**
	 * The title of the broadcast
	 * @var string
	 */
	public $title = null;

	/**
	 * The content of the broadcast
	 * @var string
	 */
	public $content = null;

	/**
	 * The link for the broadcast
	 * @var string
	 */
	public $link = null;

	/**
	 * The state of the broadcast. 1 - unread , 0 - read
	 * @var int
	 */
	public $state = null;

	/**
	 * The creation date of the braodcast
	 * @var datetime
	 */
	public $created = null;

	/**
	 * The author of the broadcast
	 * @var string
	 */
	public $created_by = null;

	/**
	 * The expiry date of the broadcast
	 * @var string
	 */
	public $expiry_date = null;

	public function __construct(&$db)
	{
		parent::__construct('#__social_broadcasts', 'id', $db);
	}

	/**
	 * Marks a broadcast as read
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function markAsRead()
	{
		$this->state = false;

		return $this->store();
	}

	/**
	 * Formats the title
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTitle()
	{
		if ($this->link) {
			return '<a href="' . $this->link . '">' . $this->title . '</a>';
		}

		return $this->title;
	}

	/**
     * Determines whether the broadcast item is expired or not
     *
     * @since   2.0
     * @access  public
     * @param   string
     * @return
     */
	public function hasExpired()
	{
		if (!$this->hasExpirationDate()) {
			return false;
		}

		// Check if it has already expired
		$current = ES::date()->toSql();

		if ($current >= $this->expiry_date) {
			return true;
		}
		
		return false;
	}

	/**
	 * Determines if the broadcast has an expiration date
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function hasExpirationDate()
	{
		if (!$this->expiry_date || $this->expiry_date == '0000-00-00 00:00:00') {
			return false;
		}

		return true;
	}
}
