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

class EasySocialControllerBadges extends EasySocialController
{
	public function __construct()
	{
		parent::__construct();

		$this->registerTask('save' , 'store');
		$this->registerTask('apply' , 'store');
		$this->registerTask('publish' 	, 'togglePublish');
		$this->registerTask('unpublish', 'togglePublish');
	}

	/**
	 * Removes badge from the site
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function remove()
	{
		ES::checkToken();

		$ids = $this->input->get('cid', array(), 'int');

		if (!$ids) {
			return $this->view->exception('COM_EASYSOCIAL_BADGES_INVALID_BADGE_ID_PROVIDED');
		}

		foreach ($ids as $id) {
			$badge = ES::table('Badge');
			$badge->load((int) $id);
			$badge->delete();
		}
		
		$this->view->setMessage('COM_EASYSOCIAL_BADGES_DELETED');
		return $this->view->call(__FUNCTION__, $task);
	}

	/**
	 * Mass assign points for users
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function massAssign()
	{
		ES::checkToken();

		// Get the file from the request
		$file = JRequest::getVar('package' , '' , 'FILES');
		$data = ES::parseCSV($file['tmp_name'], false, false);

		if (!$data) {
			return $this->view->exception('COM_EASYSOCIAL_BADGES_INVALID_CSV_FILE');
		}

		// Load up the points library
		$badges = ES::badges();

		// Collect the list of failed and successfull items
		$failed = array();
		$success = array();

		foreach ($data as $row) {
			
			// Get the user id from the csv file
			$userId = isset($row[0]) ? $row[0] : false;

			// Get the badge id
			$badgeId = isset($row[1]) ? $row[1] : false;

			// Get the date of achievement.
			$dateAchieved = isset($row[2]) ? $row[2] : false;

			// Get the custom message
			$message = isset($row[3]) ? $row[3] : false;

			// Should the item be published on the stream
			$publishStream = isset($row[4]) && $row[4] == 1 ? true : false;

			// Convert the row of items into an object.
			$obj = (object) $row;

			// Try to load the badge
			$badge = ES::table('Badge');
			$badge->load($badgeId);

			// Skip this if we don't have sufficient data
			if (!$userId || !$badgeId || !$badge->id) {
				$failed[]	= $obj;
				continue;
			}

			// Load up the user object
			$user = FD::user($userId);
			$state = $badges->create($badge, $user, $message, $dateAchieved, $publishStream);

			// Skip this if assignment failed.
			if (!$state) {
				continue;
			}

			$success[] = $obj;
		}

		$this->view->setMessage('COM_EASYSOCIAL_BADGES_CSV_FILE_PARSED_SUCCESSFULLY');
		return $this->view->call(__FUNCTION__, $success, $failed);
	}

	/**
	 * Toggles the publish state for the badges
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function togglePublish()
	{
		ES::checkToken();

		$ids = $this->input->get('cid', array(), 'int');

		if (!$ids) {
			return $this->view->exception('COM_EASYSOCIAL_BADGES_INVALID_BADGE_ID_PROVIDED');
		}

		$task = $this->getTask();

		foreach ($ids as $id) {
			$badge = ES::table('Badge');
			$badge->load((int) $id);

			$badge->$task();
		}

		$message = 'COM_EASYSOCIAL_BADGES_PUBLISHED';

		if ($task == 'unpublish') {
			$message = 'COM_EASYSOCIAL_BADGES_UNPUBLISHED';
		}

		$this->view->setMessage($message);
		return $this->view->call(__FUNCTION__, $task);
	}

	/**
	 * Saves a badge from the back end
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store()
	{
		ES::checkToken();

		// Get the badge id from the request
		$id = $this->input->get('id', 0, 'int');

		$badge = ES::table('Badge');

		if ($id) {
			$badge->load($id);
		}

		$post = $this->input->post->getArray();

		// Ensure that points threshold should always be positive integer
		$post['points_threshold'] = abs((int) $post['points_threshold']);

		$badge->bind($post);
		$state = $badge->store();

		// Process image
		$image = $this->input->files->get('image');

		if (isset($image['tmp_name']) && isset($image['error']) && $image['error'] == 0) {
			$badge->uploadAvatar($image);
		}

		$this->view->setMessage('COM_EASYSOCIAL_BADGES_UPDATED_SUCCESS');
		
		return $this->view->call(__FUNCTION__, $this->getTask(), $badge);
	}

	/**
	 * Processes the uploaded rule file.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function upload()
	{
		$file = JRequest::getVar('package', '', 'FILES');

		// Allowed extensions
		$allowed = array('zip', 'badge', 'badges');

		// Install it now.
		$rules = ES::rules();
		$state = $rules->upload($file, 'badges', $allowed);

		if ($state === false) {
			$this->view->setMessage($rules->getError(), ES_ERROR);

			return $this->view->call(__FUNCTION__);
		}

		$this->view->setMessage($state);
		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Discover .points files from the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function discoverFiles()
	{
		ES::checkToken();

		$model = ES::model('Badges');
		$paths = $this->config->get('badges.paths');
		$files	= array();

		foreach ($paths as $path) {
			$data = $model->scan($path);

			foreach ($data as $file) {
				$files[] = $file;
			}
		}

		return $this->view->call(__FUNCTION__, $files);
	}

	/**
	 * Scans for rules throughout the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function scan()
	{
		ES::checkToken();

		$file = JRequest::getVar('file', '');
		$model = ES::model('Badges');

		$obj = new stdClass();
		$obj->file = str_ireplace(JPATH_ROOT, '', $file);
		$obj->rules = $model->install($file);

		return $this->view->call(__FUNCTION__, $obj);
	}
}
