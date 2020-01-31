<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.file');

class EasyBlogImageOptimizer extends EasyBlog
{
	/**
	 * Retrieves information about a file
	 *
	 * @since	5.3.0
	 * @access	public
	 */
	public function getImageInfo($path)
	{
		static $items = array();

		if (!isset($items[$path])) {
			$items[$path] = false;
			$data = @getimagesize($path);

			if ($data !== false) {
				$items[$path] = $data;
			}
		}

		return $items[$path];
	}

	/**
	 * Optimize images
	 *
	 * @since	5.3.0
	 * @access	public
	 */
	public function optimize($pathToImage)
	{
		if (!$this->config->get('main_media_compression') || !$this->config->get('main_media_compression_key')) {
			return false;
		}

		// Get the file info
		$fileInfo = $this->getImageInfo($pathToImage);

		$post = array(
			'file' => class_exists('CURLFile', false) ? new CURLFile($pathToImage, $fileInfo['mime']) : "@" . $pathToImage,
			'service_key' => $this->config->get('main_media_compression_key'),
			'domain' => rtrim(JURI::root(), '/')
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, EASYBLOG_OPTIMIZER_SERVER);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100000);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		// @TODO: Logging for remote services

		// We need to know the response status
		if ($code == 400) {
			$result = json_decode($result);

			return;
		}

		// Resave the file
		$state = JFile::write($pathToImage, $result);

		return $state;
	}
}
