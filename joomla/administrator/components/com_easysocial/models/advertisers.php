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

ES::import('admin:/includes/model');

class EasySocialModelAdvertisers extends EasySocialModel
{
	private $_nextlimit = 0;

	public function __construct()
	{
		parent::__construct('advertisers');
	}

	/**
	 * Initializes all the generic states from the form
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function initStates()
	{
		$callback = JRequest::getVar('jscallback', '');
		$defaultFilter = $callback ? SOCIAL_STATE_PUBLISHED : 'all';

		$filter = $this->getUserStateFromRequest('state', $defaultFilter);

		$this->setState('state', $filter);

		parent::initStates();
	}

	/**
	 * Retrieve a list of ads from the site
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getItemsWithState($options = array())
	{
		$db = ES::db();
		$sql = $db->sql();

		$sql->select('#__social_advertisers');

		// Check for search
		$search = $this->getState('search');

		if ($search) {
			$sql->where('name', '%' . $search . '%', 'LIKE');
		}

		// Check for ordering
		$ordering = $this->getState('ordering');

		if ($ordering) {
			$direction = $this->getState('direction') ? $this->getState('direction') : 'DESC';

			$sql->order($ordering, $direction);
		}

		// Check for state
		$state = $this->getState('state');

		if ($state != 'all' && !is_null($state)) {
			$sql->where('state', $state);
		}

		$limit = $this->getState('limit');

		if ($limit != 0) {
			$this->setState('limit', $limit);

			// Get the limitstart.
			$limitstart = $this->getUserStateFromRequest('limitstart', 0);
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limitstart', $limitstart);

			// Set the total number of items.
			$this->setTotal($sql->getTotalSql());

			// Get the list of items
			$result = $this->getData($sql);
		} else {
			$db->setQuery($sql);
			$result = $db->loadObjectList();
		}

		if (!$result) {
			return $result;
		}

		$ads = array();

		foreach ($result as $row) {
			$ad = ES::table('Advertiser');
			$ad->bind($row);

			$ads[] = $ad;
		}

		return $ads;
	}

	/**
	 * Get all ads on the site
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function getItems($options = array())
	{
		$db = ES::db();
		$sql = $db->sql();

		$sql->select('#__social_advertisers', 'a');

		if (isset($options['name']) && $options['name']) {
			$sql->where('a.name', $options['name']);
		}

		$sql->where('a.state', SOCIAL_STATE_PUBLISHED);
		$sql->order('a.name', 'asc');

		$db->setQuery($sql);

		$result = $db->loadObjectList();

		return $result;
	}
}
