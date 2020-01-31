<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

ES::import('admin:/includes/model');

class FeedsModel extends EasySocialModel
{
	/**
	 * Retrieves a list of feeds created by a particular user.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getItems($userId)
	{
		$db 	= FD::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_feeds' );
		$sql->where( 'user_id' , $userId );
		$sql->order( 'created' , 'DESC' );
		$db->setQuery( $sql );

		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Retrieves the total number of feeds the user created
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function getTotalFeeds($userId)
	{
		$db = ES::db();
		$sql = $db->sql();

		$sql->column('COUNT(1)');
		$sql->select('#__social_feeds');
		$sql->where('user_id', $userId);
		$db->setQuery($sql);

		$result = (int) $db->loadResult();

		return $result;		
	}
}
