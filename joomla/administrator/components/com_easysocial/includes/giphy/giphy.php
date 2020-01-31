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

class SocialGiphy extends EasySocial
{
	private $base = 'https://api.giphy.com/v1/gifs';

	private $endpoints = array(
		'search' => '/search',
		'trending' => '/trending'
	);

	public function __construct()
	{
		parent::__construct();

		$this->key = $this->config->get('giphy.apikey');
		$this->ajax = ES::ajax();
		$this->connector = ES::connector();
		$this->limit = $this->config->get('giphy.limit');
	}

	/**
	 * Request data from the GIPHY API
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function getData($searchQuery = false)
	{
		if (!$this->isEnabled()) {
			return $this->ajax->reject(JText::_('COM_ES_GIPHY_FEATURE_DISABLED_ERROR_MESSAGE'));
		}

		if (!$this->key) {
			return $this->ajax->reject(JText::_('COM_ES_GIPHY_NO_API_KEY_ERROR_MESSAGE'));
		}

		$this->connector->setMethod('GET');

		if ($searchQuery) {
			$this->search($searchQuery);
		}

		if (!$searchQuery) {
			$this->trending();
		}

		$this->connector->execute();

		$data = $this->connector->getResult();
		$result = json_decode($data);

		if (isset($result->data)) {
			return $result->data;
		}

		return false;
	}

	/**
	 * Retrieve the url for a specific endpoint
	 *
	 * @since	3.2.0
	 * @access	public
	 */
	public function getUrl($endpoint)
	{
		if (!isset($this->endpoints[$endpoint])) {
			return $this->base;
		}

		$url = $this->base . $this->endpoints[$endpoint];

		return $url;
	}

	/**
	 * Search Endpoint
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function search($query)
	{
		$url = $this->getUrl('search');

		$options = array();
		$options['api_key'] = $this->key;
		$options['q'] = $query;
		$options['limit'] = $this->limit;

		return $this->connector->addUrl($url . '?' . http_build_query($options));
	}

	/**
	 * Trending Endpoint
	 *
	 * @since	3.2.0
	 * @access	public
	 */
	public function trending()
	{
		$url = $this->getUrl('trending');

		$options = array();
		$options['api_key'] = $this->key;
		$options['limit'] = $this->limit;

		return $this->connector->addUrl($url . '?' . http_build_query($options));
	}

	/**
	 * Determine whether is it enabled or not
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function isEnabled()
	{
		if (!$this->config->get('giphy.enabled')) {
			return false;
		}

		return true;
	}

	/**
	 * Determine whether is it enabled or not for conversations
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function isEnabledForConversations()
	{
		if (!$this->isEnabled() || !$this->config->get('conversations.giphy')) {
			return false;
		}

		return true;
	}

	/**
	 * Determine whether is it enabled or not for story
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function isEnabledForStory()
	{
		if (!$this->isEnabled() || !$this->config->get('stream.story.giphy')) {
			return false;
		}

		return true;
	}

	/**
	 * Determine whether is it enabled or not for comments
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function isEnabledForComments()
	{
		if (!$this->isEnabled() || !$this->config->get('comments.giphy')) {
			return false;
		}

		return true;
	}

	/**
	 * Check for a valid GIPHY URL
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function isValidUrl($url)
	{
		$pattern = "/(^|\\s)(https?:\\/\\/)?(([a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.([a-z]{2,6}))|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(:[0-9]{1,5})?(\\/.*)?/uism";

		$match = preg_match($pattern, $url);

		if (!$match) {
			return false;
		}

		// If it contains single or double quote, just return false
		if (stristr($url, '"') != false || stristr($url, "'") != false) {
			return false;
		}

		if (stristr($url, 'giphy.com') == false) {
			return false;
		}

		return true;
	}
}
