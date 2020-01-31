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

jimport('joomla.application.component.model');

ES::import('admin:/includes/model');

class EasySocialModelUrls extends EasySocialModel
{
	public function __construct($config = array())
	{
		parent::__construct('urls', $config);
	}

	/**
	 * Initializes all the generic states from the form
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function initStates()
	{
		$ordering = $this->getUserStateFromRequest('ordering', 'id');
		$direction = $this->getUserStateFromRequest('direction', 'ASC');
		$type = $this->getUserStateFromRequest('type', '');

		parent::initStates();

		$this->setState('type', $type);
		$this->setState('ordering', $ordering);
		$this->setState('direction', $direction);
	}

	/**
	 * Retrieves all sef urls that generated in the systems
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getItems($options = array())
	{
		$db = ES::db();
		$sql = $db->sql();

		$sql->select('#__social_urls');

		// Check for search
		$search = $this->getState('search');

		if ($search) {
			$sql->where('sefurl', '%' . $search . '%', 'LIKE');
		}

		// Check for search
		$type = $this->getState('type');

		if ($type == 'custom') {
			$sql->where('custom', '1');
		}

		// Check for ordering
		$ordering = $this->getState('ordering');

		if ($ordering) {
			$direction = $this->getState('direction') ? $this->getState('direction') : 'DESC';

			$sql->order($ordering, $direction);
		}

		// Set the total records for pagination.
		$this->setTotal($sql->getTotalSql());

		$urls = $this->getData($sql);

		if (!$urls) {
			return false;
		}

		return $urls;
	}

	/**
	 * delete sef urls based on ids
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function delete($ids)
	{
		$db = ES::db();

		$in = '';
		if (is_array($ids)) {
			$tmp = array();
			foreach ($ids as $id) {
				$tmp[] = $db->Quote($id);
			}
			$in = implode(',', $tmp);
		} else {
			$in = $db->Quote($ids);
		}

		$query = "delete from `#__social_urls` where `id` IN (" . $in . ")";
		$db->setQuery($query);
		$db->query();

		return true;
	}


	/**
	 * purge all sef urls
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function purge($withCustom = false)
	{
		$db = ES::db();

		$query = "delete from `#__social_urls`";
		$query .= " where `custom` = 0";

		if ($withCustom) {
			// this mean we would like to clear the urls completely.
			$query = "truncate table `#__social_urls`";
		}

		$db->setQuery($query);
		$db->query();

		return true;
	}

	/**
	 * get customized urls
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getCustomUrls()
	{
		$db = ES::db();

		$query = "select * from `#__social_urls` where `custom` = 1";
		$db->setQuery($query);

		$results = $db->loadObjectList();
		return $results;
	}


	/**
	 * get urls based on ids
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getUrls($ids = array())
	{
		if (! $ids) {
			return array();
		}

		$db = ES::db();

		$query = "select * from `#__social_urls`";
		$query .= " where `id` IN (" . implode(',', $ids) . ')';
		$db->setQuery($query);

		$results = $db->loadObjectList();
		return $results;
	}


	/**
	 * get all related urls based on object's alias
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getObjectUrls($alias)
	{
		$db = ES::db();

		$query = "select * from `#__social_urls`";
		$query .= " where (`sefurl` LIKE " . $db->Quote('%' . $alias . '/%');
		$query .= " OR `sefurl` LIKE " . $db->Quote('%/'. $alias . '%');
		$query .= " OR `sefurl` = " . $db->Quote($alias);
		$query .= ")";

		$db->setQuery($query);

		$results = $db->loadObjectList();
		return $results;
	}

	/**
	 * attempt to get menu item from sefurl.
	 * used by urlshortner.
	 *
	 * @since	3.1.5
	 * @access	public
	 */
	public function getMenuItemFromUrl($route)
	{
		$db = ES::db();

		$query = "select `sefurl` from `#__social_urls`";
		$query .= " where `sefurl` LIKE " . $db->Quote('%/'. $route);

		$db->setQuery($query);
		$sef = $db->loadResult();

		if ($sef) {
			$parts = explode('/', $sef);

			$alias = $parts[0];

			$query = "select `id` from `#__menu` where `alias` = " . $db->Quote($alias);
			$db->setQuery($query);

			$id = $db->loadResult();

			if ($id) {
				return $id;
			}

		}

		return 0;
	}
}
