<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(__DIR__ . '/app.php');

class SocialStore extends EasySocial
{
	public $key = null;
	public $urls = array(
						'generate' => 'https://stackideas.com/apps/api/generate?format=json&tmpl=component',
						'download' => 'https://stackideas.com/apps/api/download?format=json&tmpl=component',
						'purchase' => 'https://stackideas.com/apps/api/purchase?format=json&tmpl=component'
					);

	public function __construct()
	{
		parent::__construct();

		$this->key = $this->config->get('general.key');
	}

	/**
	 * Allows caller to purchase an app
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function purchase(SocialStoreApp $app)
	{
		// Ensure that the app can really be downloaded
		if (!$app->hasPaymentSupport()) {
			return false;
		}

		$endpoint = $this->urls['purchase'];

		$success = rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easysocial&controller=store&task=success&app_id=' . $app->app_id;
		$fail = rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easysocial&controller=store&task=fail&app_id=' . $app->app_id;

		$conn = ES::connector();
		$conn->addUrl($endpoint);
		$conn->setMethod('POST');
		$conn->addQuery('key', $this->key);
		$conn->addQuery('id', $app->app_id);
		$conn->addQuery('success', $success);
		$conn->addQuery('fail', $fail);
		$conn->connect();

		$result = $conn->getResult($endpoint);        
		$result = json_decode($result);

		return $result->url;
	}

	/**
	 * Allows caller to download an app
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function download(SocialStoreApp $app)
	{
		// Ensure that it can really be downloaded from the store
		if (!$app->isDownloadable() || !$app->isDownloadableFromApi()) {
			return false;
		}

		$endpoint = $this->urls['download'];

		$conn = ES::connector();
		$conn->addUrl($endpoint);
		$conn->setMethod('POST');
		$conn->addQuery('key', $this->key);
		$conn->addQuery('id', $app->app_id);
		$conn->connect();

		$contents = $conn->getResult($endpoint);        
		
		$obj = json_decode($contents);

		// @TODO: Display proper errors
		if ($obj) {
			dump($obj);
		}

		// Assuming that everything went fine, store the file with a unique name
		$path = SOCIAL_TMP . '/' . uniqid() . '.zip';

		JFile::write($path, $contents);
		
		return $path;
	}

	/**
	 * Installs the app
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function install($path)
	{
		// Assuming the path is the zip file
		$installer = ES::installer();
		$extractedPath = $installer->extract($path);

		$state = $installer->load($extractedPath);

		// Check if the installation failed
		if ($state === false) {
			$this->setError($installer->getError());

			return false;
		}

		// Install the app now
		$app = $installer->install();

		// Once it is installed, regardless of the state, delete the paths
		JFile::delete($path);
		JFolder::delete($extractedPath);

		return $app;
	}

	/**
	 * Retrieves a list of apps
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function refresh()
	{
		$endpoint = $this->urls['generate'];

		$conn = ES::connector();
		$conn->addUrl($endpoint);
		$conn->setMethod('POST');
		$conn->addQuery('key', $this->key);
		$conn->addQuery('domain', rtrim(JURI::root(), '/'));
		$conn->connect();
		$result = $conn->getResult($endpoint);

		if (!$result) {
			return false;
		}

		$items = json_decode($result);

		if (isset($items->code) && $items->code == 404) {
			$this->setError($items->message);
			return false;
		}

		$items = $items->result;


		foreach ($items as $item) {
			$appId = (int) $item->app_id;


			$table = ES::table('Store');
			$table->load(array('app_id' => $appId));
			$table->bind($item);
			
			$table->raw = json_encode($item);

			$table->store();
		}

		return true;
	}

	/**
	 * Generates a new app item
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function getApp($id)
	{
		$app = new SocialStoreApp($id);

		return $app;
	}
}