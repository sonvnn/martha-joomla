<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');


class SocialFileCache
{
	protected $_newUrls = array();

	protected $_urlCount = 0;
	protected $_newUrlCount = 0;
	protected $_isLocked = false;

	/**
	 * Used to register shutdown function.
	 *
	 * @since	3.1.5
	 * @access	public
	 */
	public function __construct() 
	{
		register_shutdown_function(array( $this, 'writeCache'));
	}

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	3.1
	 * @access	public
	 */
	public static function getInstance()
	{
		static $obj = null;

		if (is_null($obj)) {
			$obj = new self();
		}

		return $obj;
	}

	/**
	 * @since	1.4
	 * @access	public
	 * @param   null
	 * @return  SocialFileCache
	 */
	public static function factory()
	{
		return new self();
	}

	/**
	 * Get sef url from cache
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getSefUrl($oriUrl, $skipNew = false)
	{
		$this->loadCache();

		$key = md5($oriUrl);

		if (isset($this->_urls[$key])) {
			return $this->_urls[$key];
		}

		if (!$skipNew && isset($this->_newUrls[$key])) {
			$data = implode('||', $this->_newUrls[$key]);
			return $data;
		}

		return false;
	}

	/**
	 * This method is used to refresh the cache file
	 * or prevent the cache file from being 'cached'
	 * when using php include
	 *
	 * @since	3.1.5
	 * @access	public
	 */
	public function refreshCacheFile()
	{
		$check = true;
		$filename = $this->getFilePath();

		// in any case if the cache file already refreshed, 
		// then we should skip the subsequence processing.

		if ($check && function_exists('opcache_invalidate')) {
			@opcache_invalidate($filename);
			$check = false;
		}

		if ($check && function_exists('apc_compile_file')) {
			@apc_compile_file($filename);
			$check = false;
		}
		
		if ($check && function_exists('wincache_refresh_if_changed')) {
			@wincache_refresh_if_changed(array($filename));
			$check = false;
		}

		if ($check && function_exists('xcache_asm')) {
			@xcache_asm($filename);
			$check = false;
		}

		return true;
	}

	/**
	 * Get non sef url from cache
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getNonSefUrl($sef, $skipNew = false)
	{
		$this->loadCache();

		$url = null;

		// check in cached urls;
		if ($this->_urls) {
			foreach ($this->_urls as $key => $value) {
				$data = explode('||', $value);

				$sefurl = $data[1];

				if ($sefurl == $sef) {
					$url = $value;
					break;
				}
			}
		}

		// check in new urls;
		if (!$skipNew && $this->_newUrls) {
			foreach ($this->_newUrls as $key => $data) {

				$sefurl = $data[1];

				if ($sefurl == $sef) {
					$url = implode('||', $data);
					break;
				}
			}
		}


		// okay we found the url, let format the data and return.
		if ($url) {

			$data = explode('||', $url);

			$nonsef = $data[0];
			$sefurl = $data[1];

			$obj = new stdClass();
			$obj->sefurl = $sefurl;
			$obj->rawurl = $nonsef;

			return $obj;
		}


		// nothing found.
		return false;

	}

	/**
	 * Get new urls that need to be process
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getNewUrls()
	{
		return $this->_newUrls;
	}

	/**
	 * Add new urls into container for later processing
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function addNewUrls($oriUrl, $data)
	{
		if (($this->_urlCount + $this->_newUrlCount) >= SOCIAL_SEF_LIMIT) {
			return;
		}

		$key = md5($oriUrl);

		if (!isset($this->_newUrls[$key])) {
			$this->_newUrls[$key] = $data;
			$this->_newUrlCount++;
		}

	}

	/**
	 * Load urls from cache file
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function loadCache()
	{
		// static $loaded = false;
		static $loaded = null;

		if (is_null($loaded)) {

			// get cache file
			$file = $this->getFilePath();

			$this->_urls = array();

			// acquire lock.
			$this->acquireLock();

			if (JFile::exists($file)) {

				// attemp to refresh the cache file before we include.
				$this->refreshCacheFile();

				include($file);
				$loaded = !empty($this->_urls);

			} else {

				// file not found.
				$loaded = true;
			}

			$this->_urlCount = !empty($this->_urls) ? count($this->_urls) : 0;
		}
	}

	/**
	 * Write new urls into cache file
	 * Currently this method is being called by system plugin :: onAfterRender()
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function writeCache()
	{
		// first we check if there are any error caused by the sef cache file or not.
		$lasterror = error_get_last();

		// Some errors from /media/com_easysocial/cache/45e5d516a83341ca921fe20e61870119-cache.php
		// ignore warning and notice errors.
		$cachefilename = md5(SOCIAL_FILE_CACHE_FILENAME) . '-';
		if ($lasterror && isset($lasterror['type']) && ($lasterror['type'] != '2' && $lasterror['type'] != '8') 
			&& isset($lasterror['file']) && stristr($lasterror['file'], '/media/com_easysocial/cache/' . $cachefilename) !== false) {

			// move the cache file into error file.
			$errorFile = $this->getFilePath('error');
			$srcFile = $this->getFilePath();
			JFile::copy($srcFile, $errorFile);

			// log the error into error-log.php file.
			$errorLogFile = str_replace('-error.php', '-errorlog.php', $errorFile);
			ob_start();
			echo "\n";
			echo "------ " . ES::date()->toSql() . " ------- \n";
			var_dump($lasterror);
			echo "\n--------------------------\n";
			$errorMessage = ob_get_contents();
			ob_end_clean();

			if (JFile::exists($errorLogFile)) {
				$prevContent = JFile::read($errorLogFile);
				$errorMessage = $prevContent . $errorMessage;
			}

			JFile::write($errorLogFile, $errorMessage);

			// turn off sef caching and set the warning message
			// so that admin are aware.
			$errorFile = str_replace(JPATH_ROOT, '', $errorFile);
			$warning = JText::sprintf('COM_ES_SEF_CACHE_WARNING', $errorFile, $errorLogFile);
			ES::disableSEFCache($warning);

			// now delete the sef cache file
			$this->purge();

			// and release the lock here.
			$this->releaseLock(true);

			// redirect to ES dashboard page.
			$url = ESR::_('index.php?option=com_easysocial&view=dashboard');
			header('Location: ' . $url);
			exit;
		}

		// check if cache folder exist or not
		if (!JFolder::exists(SOCIAL_FILE_CACHE_DIR)) {
			JFolder::create(SOCIAL_FILE_CACHE_DIR);
		}

		$newUrls = $this->getNewUrls();

		if (count($newUrls) && $this->_isLocked) {

			// need to reload the urls
			$this->loadCache();

			// cache file content
			$header = '';
			$content = '';

			// get cache file
			$filepath = $this->getFilePath();
			$isNewCache = false;

			if (!JFile::exists($filepath)) {
				$header = $this->generateHeader(__LINE__);
				$isNewCache = true;
			}

			// in an event where cache file include might be 'cached' by php cache, e.g. opcache
			// and if this happen, this might cause duplicate urls being written into cache file 
			// and causing the filesize to increase drastrically.
			// To solve this, we need to check if the file size above the allowed size or not.
			$resetCacheFile = false;

			if (!$isNewCache) {
				$filesize = @filesize($filepath);
				$filesize = (int) $filesize;

				if ($filesize > 1024) {
					$inKB = $filesize / 1024;
					if ($inKB >= SOCIAL_SEF_FILESIZE) {
						$resetCacheFile = true;
					}
				}
			}

			if (!$isNewCache && ($this->_urlCount + $this->_newUrlCount >= SOCIAL_SEF_LIMIT)) {
				// the number of urls reach the threshold. let remove all urls in the cache file.
				$resetCacheFile = true;
			}

			if ($resetCacheFile) {
				JFile::delete($filepath);

				// regenerate the header for saving.
				$header = $this->generateHeader(__LINE__);
				$isNewCache = true;
			}

			$date = ES::date();
			$startLog = $date->toSql();

			foreach ($newUrls as $key => $row) {

				// further check if key really not exists
				if (isset($this->_urls[$key])) {
					// dont write.
					continue;
				}

				$nonSef = $row[0];
				$sef = $row[1];

				$value = addslashes($nonSef) . '||' . $sef;
				$this->_urls[$key] = $value;

				$content .= "\n" . '$this->_urls[\'' . $key . '\']=\'' . $value . '\';';
			}

			if ($content) {

				$fp = fopen($filepath, "ab");
				if ($fp) {

					// add the start log time here.
					$start = "\n" . '// startLog=\'' . $startLog . '\';';
					$content = $start . $content;

					// check if we need to include header or not.
					if ($header) {
						$line = '';

						// since we are using fgets to read the 1st line, we need to use read mode.
						$fp2 = fopen($filepath, "rb");;
						if ($fp2) {
							$line = fgets($fp2);
						}
						fclose($fp2);

						if (stristr($line, '<?php') === false) {
							$content = $header . $content;
						}
					}

					// get the end log timestamp
					$date = ES::date();
					$endLog = $date->toSql();

					$content .= "\n" . '// endLog=\'' . $endLog . '\';';

					fwrite($fp, $content);
					fclose($fp);
				}
			}

			// lets unset from newurls
			foreach ($newUrls as $key => $row) {
				unset($this->_newUrls[$key]);
			}
		}

		// lets release the lock
		$this->releaseLock();

		return true;
	}

	/**
	 * Return cache file path
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getFilePath($type = 'cache')
	{
		$filename = md5(SOCIAL_FILE_CACHE_FILENAME);
		$filepath = SOCIAL_FILE_CACHE_DIR . '/' . $filename;

		if ($type == 'lock') {
			$filepath .= '-lock.php';
			return $filepath;
		}

		if ($type == 'error') {
			$filepath .= '-error.php';
			return $filepath;
		}

		$filepath .= '-cache.php';

		return $filepath;
	}

	/**
	 * remove cache file.
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function purge($customUrls = array())
	{
		if ($customUrls) {

			// load cache urls
			$this->loadCache();

			foreach ($customUrls as $custom) {
				$sef = $custom->sefurl;

				// check in cached urls;
				if ($this->_urls) {
					foreach ($this->_urls as $key => $value) {

						$data = explode('||', $value);

						$sefurl = $data[1];

						if ($sefurl == $sef) {

							// assign into newUrls so that at shutdown, these url will be
							// added into the cache file.
							$this->_newUrls[$key] = $data;
							$this->_newUrlCount++;
							break;
						}
					}
				}
			}

			// reset the urls variable.
			$this->_urls = array();
			$this->_urlCount = 0;
		}

		$filepath = $this->getFilePath();

		if (JFile::exists($filepath)) {
			JFile::delete($filepath);
		}

		return;
	}


	/**
	 * update single item in cache file.
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function updateCacheItem($newSef, $oldSef, $urlTbl)
	{
		// load cache urls
		$this->loadCache();

		if ($this->_urls) {
			foreach ($this->_urls as $key => $value) {

				$data = explode('||', $value);

				$sefurl = $data[1];

				if ($sefurl == $oldSef) {

					// assign into newUrls so that at shutdown, these url will be
					// added into the cache file.
					$this->_newUrls[$key] = array($data[0], $newSef);
					$this->_newUrlCount++;

					// clear this from the memory
					unset($this->_urls[$key]);

					break;
				}
			}
		}

		// the rest will be handle by removeCacheItem
		$this->removeCacheItems(array($urlTbl));

		return true;
	}

	/**
	 * remove entries from cache file.
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function removeCacheItems($urls = array())
	{
		if (!$urls) {
			return true;
		}

		// load cache urls
		$this->loadCache();

		if ($this->_urls) {
			foreach ($this->_urls as $key => $value) {

				$data = explode('||', $value);
				$sefurl = $data[1];

				$exists = false;

				foreach ($urls as $url) {
					if ($sefurl == $url->sefurl) {
						$exists = true;
						break;
					}
				}

				if (!$exists) {
					$this->_newUrls[$key] = $data;

					// clear this from the memory
					unset($this->_urls[$key]);
				}
			}
		}

		// let the shutdown function do the job.
		$this->_newUrlCount = count($this->_newUrls);
		// $this->_urls = array();

		$this->purge();

		return true;
	}

	/**
	 * Generate header content used in cache file.
	 *
	 * @since	3.1
	 * @access	private
	 */
	private function generateHeader($line = '')
	{
		$content = "<?php \n";
		$content .= "// " . $line . "\n";
		$content .= "if (!defined('_JEXEC')) die('Unauthorized Access');" . "\n";

		return $content;
	}

	/**
	 * Acquire lock for writing new urls into cache file
	 *
	 * @since	3.1
	 * @access	private
	 */
	private function acquireLock()
	{
		$check = false;
		$lockFile = $this->getFilePath('lock');
		$now = time();

		do {

			// try open the lock file with x mode. if the file is there, fopen should return false with a warning.
			// lets supress that warning
			$fp = @fopen($lockFile, "x");

			if ($fp) {

				$state = fwrite($fp, $now);
				$closed = fclose($fp);

				$this->_isLocked = !empty($state) && $closed;

			} else {

				// incase the previoue writing did not remove the lock properly.
				// let check for the previous stored time in the lock file.
				// if more than 30 secs, mean someting not right.
				// let release the lock

				$time = @file_get_contents($lockFile);
				$time = (int) trim($time);

				if (($now - $time) > 30) {
					$this->_isLocked = $this->releaseLock(true);
					$check = true;
				} else {
					// stop the lock acquaring. this will also  prevent the cache writing from writing
					// since we failed to acquire the lock. This also mean, the page will be displayed faster
					// and at later time, we can always rewrite again.
					$check = false;
				}
			}

		} while (!$this->_isLocked && $check);

		return $this->_isLocked;
	}

	/**
	 * Release the lock
	 *
	 * @since	3.1
	 * @access	private
	 */
	private function releaseLock($force = false)
	{
		$lockFile = $this->getFilePath('lock');

		if ($this->_isLocked || $force) {
			if (JFile::exists($lockFile)) {
				$this->_isLocked = !JFile::delete($lockFile);
			}
		}

		return $this->_isLocked;
	}

}
