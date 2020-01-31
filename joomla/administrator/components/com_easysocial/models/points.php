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

class EasySocialModelPoints extends EasySocialModel
{
	public function __construct($config = array())
	{
		parent::__construct('points', $config);
	}

	/**
	 * Initializes all the generic states from the form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function initStates()
	{
		$state 	= $this->getUserStateFromRequest( 'published' , 'all' );
		$filter	= $this->getUserStateFromRequest( 'extension' , 'all' );

		$this->setState( 'filter'	, $filter );
		$this->setState( 'published', $state );

		parent::initStates();
	}

	/**
	 * Get's the unique extensions from all the rules.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	Array
	 */
	public function getExtensions()
	{
		$db 	= FD::db();
		$sql	= $db->sql();

		$sql->select( '#__social_points' );
		$sql->column( 'extension', '', 'distinct' );

		$db->setQuery( $sql );
		$result 	= $db->loadColumn();

		return $result;
	}

	/**
	 * Retrieves the list of points achievers within a date range
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getReports($start, $end, $search = null)
	{
		$db = FD::db();
		$sql = $db->sql();

		$startDate = ES::date($start);
		$endDate = ES::date($end);

		$query = array();
		$query[] = 'SELECT a.' . $db->qn('user_id') . ', SUM(a.' . $db->qn('points') . ') AS ' . $db->qn('total');
		$query[] = 'FROM ' . $db->qn('#__social_points_history') . ' AS a';
		$query[] = 'INNER JOIN ' . $db->qn('#__users') . ' AS b';
		$query[] = 'ON a.' . $db->qn('user_id') . ' = b.' . $db->qn('id');
		$query[] = 'WHERE a.' . $db->qn('created') . ' BETWEEN ' . $db->Quote($startDate->toSql()) . ' AND ' . $db->Quote($endDate->toSql());

		if (!is_null($search)) {
			$search = '%' . $search . '%';

			$query[] = 'AND (';
			$query[] = 'b.`username` LIKE ' . $db->Quote($search);
			$query[] = 'OR';
			$query[] = 'b.`name` LIKE ' . $db->Quote($search);
			$query[] = ')';
		}

		$query[] = 'GROUP BY a.' . $db->qn('user_id');
		$query[] = 'ORDER BY ' . $db->qn('total') . ' DESC';

		$query = implode(' ', $query);

		$sql->raw($query);

		// echo $sql->debug();exit;

		$db->setQuery($sql);

		$result = $db->loadObjectList();

		return $result;
	}


	/**
	 * Retrieves a list of points from the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getItems($options = array())
	{
		$db = ES::db();
		$sql = $db->sql();

		$sql->select('#__social_points');

		$state = $this->getState('published', 'all');

		// If user passed in option['published'], we use it.
		if (isset($options['published'])) {
			$state = $options['published'];
		}

		if ($state != null && $state != 'all') {
			$sql->where( 'state', $state );
		}

		// Determines if we need to filter by extension
		$filter = $this->getState('filter');

		if ($filter != null && $filter != 'all') {
			$sql->where('extension', $filter);
		}

		// Determines if we need to perform searches
		$search = $this->getState('search');

		if ($search) {
			$sql->where('title' , '%' . $search . '%', 'LIKE');
		}

		$ordering = $this->getState('ordering');

		if ($ordering) {
			$direction = $this->getState('direction');

			$sql->order($ordering, $direction);
		}

		$limit = $this->getState('limit', 0);

		// If user passed in a custom limit, we need to respect that
		if (isset($options['limit'])) {
			$limit = $options['limit'];
		}

		if ($limit != 0) {
			$this->setState('limit', $limit);

			// Get the limitstart.
			$limitstart 	= $this->getUserStateFromRequest( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $sql->getTotalSql() );

			// Get the list of users
			$result 	= parent::getData( $sql->getSql() );
		}
		else
		{
			$db->setQuery( $sql );
			$result 	= $db->loadObjectList();
		}

		if( !$result )
		{
			return false;
		}

		// We want to pass back a list of PointsTable object.
		$points 	= array();

		foreach( $result as $row )
		{
			$point 	= FD::table( 'Points' );
			$point->bind( $row );

			$point->loadLanguage();

			$points[]	= $point;
		}

		return $points;
	}

	/**
	 * Returns the total number of items for the current query
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function getTotal()
	{
		return $this->total;
	}

	/**
	 * Retrieves the points history for a user
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getHistory($userId, $options = array())
	{
		$db = ES::db();
		$query = $db->sql();

		$query->select('#__social_points_history' , 'a' );
		$query->column('a.*' );
		$query->column('b.title', 'points_title' );
		$query->column('b.extension');
		$query->join( '#__social_points' , 'b' );
		$query->on( 'b.id' , 'a.points_id' );

		$query->where( 'a.user_id', $userId );

		if (isset($options['pointsId']) && $options['pointsId']) {
			$query->where('a.points_id', $options['pointsId']);
		}

		$query->where( 'a.state', SOCIAL_STATE_PUBLISHED );
		$query->order( 'a.created' , 'DESC' );

		$limit 	= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : 0;

		if( $limit != 0 )
		{
			$this->setState( 'limit' , $limit );

			// Get the limitstart.
			$limitstart 	= JRequest::getInt( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $query->getTotalSql() );

			// Get the list of users
			$result 	= parent::getData( $query->getSql() );
		}
		else
		{
			$db->setQuery( $query );
			$result 	= $db->loadObjectList();
		}


		if( !$result )
		{
			return false;
		}

		$histories 	= array();

		// Load admin language file as well
		FD::language()->loadAdmin();

		$loadedLanguage = array();

		foreach( $result as $row )
		{
			$history 	= FD::table( 'PointsHistory' );
			$history->bind( $row );

			if( $row->extension !== SOCIAL_COMPONENT_NAME && !empty( $row->extension ) && !in_array( $row->extension, $loadedLanguage ) )
			{
				FD::language()->load( $row->extension, JPATH_ROOT );
				FD::language()->load( $row->extension, JPATH_ADMINISTRATOR );

				$loadedLanguage[] = $row->extension;
			}

			$history->points_title 	= JText::_( $row->points_title );

			$histories[]	= $history;
		}

		return $histories;
	}

	/**
	 * Retrieves the total points a user has. (legacy)
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getPoints($userId)
	{
		$db = ES::db();
		$query = $db->sql();

		$query->select('#__social_points_history');
		$query->column('points' , '', 'sum');
		$query->where('user_id' , $userId);
		$query->where('state' , SOCIAL_STATE_PUBLISHED);

		$db->setQuery($query);
		$points = $db->loadResult();

		if (!$points) {
			return 0;
		}

		return $points;
	}

	/**
	 * Retrieve total points that the user has based on the points ids
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getPointsById($userId, $pointsId = false)
	{
		if (!$pointsId) {
			return array();
		}

		$db = ES::db();

		$query = $db->sql();

		$query->select('#__social_points_history');
		$query->column('points_id');
		$query->column('points', 'total_points', 'sum');
		$query->where('user_id', $userId);

		if (is_array($pointsId)) {
			$query->where('points_id', $pointsId, 'IN');
		} else if ($pointsId) {
			$query->where('points_id', $pointsId);
		}

		$query->where('state', SOCIAL_STATE_PUBLISHED);
		$query->group('points_id');

		$db->setQuery($query);

		$points = $db->loadObjectList();

		if (!$points) {
			return array();
		}

		$pointsArray = array();

		foreach ($points as $data) {
			$pointsArray[$data->points_id] = $data->total_points;
		}

		return $pointsArray;
	}

	/**
	 * Compute total points of a user based on two points id given
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function computeTotalPoints($userId, $pointsIncreaseId, $pointsDecreaseId)
	{
		$db = ES::db();

		$sql = $db->sql();

		$query = array();
		$query[] = 'SELECT sum(IF(`user_id` = ' . $userId . ' AND `points_id` = ' . $pointsIncreaseId . ', `points`, 0)) +';
		$query[] = 'sum(IF(`user_id` = ' . $userId . ' AND `points_id` = ' . $pointsDecreaseId . ', `points`, 0))';
		$query[] = ' AS `final_points`';
		$query[] = 'FROM ' . $db->qn('#__social_points_history');
		$query[] = 'WHERE ' . $db->qn('state') . ' = 1';

		$query = implode(' ', $query);

		$sql->raw($query);
		$db->setQuery($sql);

		$points = $db->loadResult();

		if (!$points) {
			return 0;
		}

		return $points;
	}

	/**
	 * Retrieves the list of achievers for this point
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getAchievers( $pointsId )
	{
		$db 		= FD::db();
		$query 		= $db->sql();

		$query->select( '#__social_points_history', 'a' );
		$query->column( 'a.user_id', '', 'distinct' );
		$query->where( 'a.points_id' , $pointsId );

		$query->join( '#__users' , 'uu' , 'INNER' );
		$query->on( 'a.user_id' , 'uu.id' );

		if (FD::config()->get('users.blocking.enabled') && !JFactory::getUser()->guest) {
			$query->leftjoin( '#__social_block_users' , 'bus');

			$query->on('(');
			$query->on( 'uu.id' , 'bus.user_id' );
			$query->on( 'bus.target_id', JFactory::getUser()->id);
			$query->on(')');

			$query->on('(', '', '', 'OR');
			$query->on( 'uu.id' , 'bus.target_id' );
			$query->on( 'bus.user_id', JFactory::getUser()->id );
			$query->on(')');

			$query->isnull('bus.id');
		}

		$query->where( 'uu.block' , '0' );

		$db->setQuery( $query );

		$rows 	= $db->loadColumn();

		if( !$rows )
		{
			return $rows;
		}

		$achievers 	= array();

		foreach( $rows as $id )
		{
			$achiever 		= FD::user( $id );

			if( $achiever )
			{
				$achievers[]	= $achiever;
			}

		}

		return $achievers;
	}

	/**
	 * Retrieves the daily points a user achieved
	 *
	 * @since	3.0.0
	 * @access	public
	 */
	public function getTotalPointsAchieved(SocialUser $user, SocialDate $date)
	{
		$dateString = $date->format('Y-m-d');

		$start = $dateString . ' 00:00:01';
		$end = $dateString . ' 23:59:59';

		$db = ES::db();
		$query = array();

		$query[] = 'SELECT SUM(' . $db->qn('points') . ') FROM ' . $db->qn('#__social_points_history');
		$query[] = 'WHERE ' . $db->qn('user_id') . '=' . $db->Quote($user->id);
		$query[] = 'AND (';
		$query[] = $db->qn('created') . '>=' . $db->Quote($start);
		$query[] = 'AND';
		$query[] = $db->qn('created') . '<=' . $db->Quote($end);
		$query[] = ')';

		$db->setQuery($query);
		$total = (int) $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves the total number of achievers for a point.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getTotalAchievers( $pointsId )
	{
		$db 	= FD::db();
		$query 	= $db->sql();

		$query->select( '#__social_points_history', 'a' );
		$query->column( 'a.user_id', 'total', 'count distinct' );
		$query->where( 'a.points_id' , $pointsId );

		$query->join( '#__users' , 'uu' , 'INNER' );
		$query->on( 'a.user_id' , 'uu.id' );

		if (FD::config()->get('users.blocking.enabled') && !JFactory::getUser()->guest) {
			$query->leftjoin( '#__social_block_users' , 'bus');

			$query->on('(');
			$query->on( 'uu.id' , 'bus.user_id' );
			$query->on( 'bus.target_id', JFactory::getUser()->id);
			$query->on(')');

			$query->on('(', '', '', 'OR');
			$query->on( 'uu.id' , 'bus.target_id' );
			$query->on( 'bus.user_id', JFactory::getUser()->id );
			$query->on(')');

			$query->isnull('bus.id');
		}

		$query->where( 'uu.block' , '0' );

		$db->setQuery( $query );

		$total 	= $db->loadResult();

		if( !$total )
		{
			return 0;
		}

		return $total;
	}

	/**
	 * Scans through the given path and see if there are any *.points file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The path type. E.g: components , plugins, apps , modules
	 * @return
	 */
	public function scan( $path )
	{
		jimport( 'joomla.filesystem.folder' );

		$files 	= array();

		if( $path == 'admin' || $path == 'components' )
		{
			$directory	= JPATH_ROOT . '/administrator/components';
		}

		if( $path == 'site' )
		{
			$directory	= JPATH_ROOT . '/components';
		}

		if( $path == 'apps' )
		{
			$directory 	= SOCIAL_APPS;
		}

		if( $path == 'fields' )
		{
			$directory 	= SOCIAL_FIELDS;
		}

		if( $path == 'plugins' )
		{
			$directory 	= JPATH_ROOT . '/plugins';
		}

		if( $path == 'modules' )
		{
			$directory	 = JPATH_ROOT . '/modules';
		}

		$files 		= JFolder::files( $directory , '.points$' , true , true );

		return $files;
	}

	/**
	 * Resets points for a specific user on the site
	 *
	 * @since	1.4.7
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function reset($userId)
	{
		$db = ES::db();
		$sql = $db->sql();

		$sql->delete('#__social_points_history');
		$sql->where('user_id', $userId);

		$db->setQuery($sql);

		return $db->Query();
	}

	/**
	 * Given a path to the file, install the points.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The path to the .points file.
	 * @return	bool		True if success false otherwise.
	 */
	public function install( $path )
	{
		// Import platform's file library.
		jimport( 'joomla.filesystem.file' );

		// Read the contents
		$contents 	= JFile::read( $path );

		// If contents is empty, throw an error.
		if (empty($contents)) {
			$this->setError( JText::_( 'Unable to read points file' ) );
			return false;
		}

		$json 		= FD::json();
		$data 		= $json->decode( $contents );

		// @TODO: Double check that this file is a valid JSON file.

		// Ensure that it's in an array form.
		$data 		= FD::makeArray( $data );

		// Let's test if there's data.
		if (empty($data)) {
			$this->setError( JText::_( 'Unable to read points file' ) );
			return false;
		}

		$result 	= array();

		foreach( $data as $row )
		{
			// Load the tables
			$point 	= FD::table( 'Points' );

			// If this already exists, we need to skip this.
			$state 	= $point->load( array( 'command' => $row->command , 'extension' => $row->extension ) );

			if( $state )
			{
				continue;
			}

			$point->bind( $row );

			// If there is params set on the rule file, we need to be able to detect it.
			$params 	= null;

			if (isset($row->params) && $row->params) {
				// Ensure that the params are json encoded.
				$point->params 	= FD::json()->encode($row->params);
			}

			// Store it now.
			$point->store();

			// Load language file.
			JFactory::getLanguage()->load( $row->extension , JPATH_ROOT . '/administrator' );

			$result[]	= JText::_( $point->title );
		}

		return $result;
	}
}
