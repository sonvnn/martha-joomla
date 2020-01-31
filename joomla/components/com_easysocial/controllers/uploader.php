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

class EasySocialControllerUploader extends EasySocialController
{
	/**
	 * Allows caller to temporarily upload a file
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function uploadTemporary()
	{
		ES::requireLogin();
		ES::checkToken();

		// Get the type of storage
		$type = $this->input->get('type', '', 'word');

		// Get the limit
		$limit = $this->config->get($type . '.attachments.maxsize');

		// Set uploader options
		$options = array('name' => 'file', 'maxsize' => $limit . 'M');

		// Get uploaded file
		$uploader = ES::uploader($options);

		// Known types that only allows image uploads
		$filter = '';
		$requireImageFiltering = array('comments');
		$uploadTmp = true;

		if (in_array($type, $requireImageFiltering)) {
			$filter = 'image';
			$uploadTemp = false;
		}

		$data = $uploader->getFile('', $filter);

		// If there was an error getting uploaded file, stop.
		if ($data instanceof SocialException) {
			$this->view->setMessage($data);
			return $this->view->call(__FUNCTION__);
		}

		if (!$data) {
			$this->view->setMessage('COM_EASYSOCIAL_UPLOADER_FILE_DID_NOT_GET_UPLOADED', ES_ERROR);
			return $this->view->call(__FUNCTION__);
		}

		// Let's get the temporary uploader table.
		$uploader = ES::table('Uploader');
		$uploader->user_id = $this->my->id;

		// Bind the data on the uploader
		$uploader->bindFile($data, $uploadTmp);

		// Try to save the uploader
		$state = $uploader->store();

		if (!$state) {
			$this->view->setMessage($uploader->getError(), ES_ERROR);

			return $this->view->call(__FUNCTION__, $uploader);
		}

		if ($filter == 'image') {
			// Load up the image library to ensure the orientation is correct
			$image = ES::image();
			$image->load($data['tmp_name']);

			// Fix image orientation if image is not animated
			if (!$image->isAnimated()) {

				// Must save the image using this method to fix orientation of vertical image. #1243
				$image->rotate(0);

				jimport('joomla.filesystem.file');

				// Remove existing image first
				if (JFile::exists($uploader->path)) {
					JFile::delete($uploader->path);
				}

				// Save the image
				$state = $image->save($uploader->path);
			}

			$optimizer = ES::imageoptimizer();
			$optimizer->optimize($uploader->path);
		}

		return $this->view->call(__FUNCTION__, $uploader);
	}

	/**
	 * Deletes a file from the system.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function delete()
	{
		ES::requireLogin();
		ES::checkToken();

		// Get the uploader id
		$id = $this->input->get('id', 0, 'int');

		$uploader = ES::table('Uploader');
		$uploader->load($id);

		// Check if the user is really permitted to delete the item
		if (!$id || !$uploader->id || $uploader->user_id != $this->my->id) {
			return $this->view->call(__FUNCTION__);
		}

		$state = $uploader->delete();

		return $this->view->call(__FUNCTION__);
	}

}
