<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(__DIR__ . '/helpers/simplehtml.php');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class SocialCrawler
{
	/**
	 * Available hooks.
	 * @var	Array
	 */
	private $hooks	= array();

	/**
	 * Raw contents
	 * @var	string
	 */
	private $contents	= null;

	public static function factory()
	{
		$obj = new self();

		return $obj;
	}

	/**
	 * Normalize the url
	 *
	 * @since	1.3
	 * @access	public
	 */
	public function normalizeUrl($url)
	{
		$url = trim($url);

		if (stristr($url, 'http://') === false && stristr($url, 'https://') === false) {
			$url = 'http://' . $url;
		}

		return $url;
	}

	/**
	 * Normalizes the output of a page
	 *
	 * @since	1.3
	 * @access	public
	 */
	public function normalizeContent($url, $content)
	{
		$info = parse_url($url);

		$content = str_ireplace('src="//', 'src="' . $info['scheme'] . '://' , $content);

		return $content;
	}

	/**
	 * force the text to be 'utf-8'
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function forceUTF8($text)
	{
		if (is_array($text)) {
			foreach($text as $k => $v) {
				$text[$k] = $this->forceUTF8($v);
			}
			return $text;
		}

		$max = strlen($text);
		$buf = "";
		for($i = 0; $i < $max; $i++){
			$c1 = $text{$i};
			if($c1>="\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
			  $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
			  $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
			  $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
				if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2;
						$i++;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} elseif($c1 >= "\xe0" & $c1 <= "\xef"){ //looks like 3 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2 . $c3;
						$i = $i + 2;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} elseif($c1 >= "\xf0" & $c1 <= "\xf7"){ //looks like 4 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2 . $c3;
						$i = $i + 2;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} else { //doesn't look like UTF8, but should be converted
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = (($c1 & "\x3f") | "\x80");
						$buf .= $cc1 . $cc2;
				}
			} elseif(($c1 & "\xc0") == "\x80"){ // needs conversion
					$cc1 = (chr(ord($c1) / 64) | "\xc0");
					$cc2 = (($c1 & "\x3f") | "\x80");
					$buf .= $cc1 . $cc2;
			} else { // it doesn't need convesion
				$buf .= $c1;
			}
		}
		return $buf;
	}

	/**
	 * Scrapes url and retrieves the content from the particular page
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function scrape($url)
	{
		$url = $this->normalizeUrl($url);

		$connector = ES::connector();
		$connector->addUrl($url);
		$connector->addOption(CURLOPT_USERAGENT, "Facebot");
		$connector->connect();
		$contents = $connector->getResult($url);

		// Normalize the contents
		$contents = $this->normalizeContent($url, $contents);

		// Make sure the content is utf-8 as SocialSimpleHTML can only support UTF-8
		// $contents = mb_convert_encoding($contents, "UTF-8");
		if (!mb_detect_encoding($contents, 'UTF-8', true)) {

			// Determine if charset is windows-1251
			$header = $connector->getResult($url, true);

			preg_match_all("/charset=([^()<>@,;:\"\/[\]?.=\s]*)/i", $header, $matches);

			if ($matches && isset($matches[1]) && $matches[1]) {
				$charset = $matches[1][0];
			}

			if ($charset == 'windows-1251') {
				$contents = mb_convert_encoding($contents, "utf-8", "windows-1251");
			} else {
				$contents = $this->forceUTF8($contents);
			}
		}

		// Get the parser
		$parser	= SocialSimpleHTML::str_get_html($contents);

		if (!$parser) {
			return false;
		}

		$amp = $parser->find('html[amp]');

		if ($amp && isset($amp[0])) {
			$canonical = $parser->find('link[rel=canonical]');
			if ($canonical && isset($canonical[0])) {
				$url = $canonical[0]->href;
				return $this->scrape($url);
			}
		}

		$httpEquiv = $parser->find('meta[http-equiv=refresh]');

		if ($httpEquiv && isset($httpEquiv[0])) {
			$httpEquiv = $httpEquiv[0]->attr['content'];

			// Check if this refresh value has url in it.
			$pattern = '/url=["\'](.*)["\']/i';
			preg_match($pattern, $httpEquiv, $matches);

			if (!empty($matches)) {
				return $this->scrape($matches[1]);
			}
		}

		$info = parse_url($url);
		$uri = $info['scheme'] . '://' . $info['host'];

		// Get a list of available hooks
		$hooks = $this->getHooks($url, $parser, $contents);

		$result = new stdClass();

		// Get the standard oembed data first
		if (!isset($result->oembed)) {
			$base = new SocialCrawlerAbstract($url, $parser, $contents);
			$result->oembed = $base->getOembed();

			if (!$result->oembed) {
				$result->oembed = new stdClass();
			}
		}

		// Let the hooks perform it's magic
		foreach ($hooks as $hook) {
			$hook->process($result);
		}

		// ALlow apps to process crawled urls
		ES::apps()->load(SOCIAL_TYPE_USER);
		$dispatcher = ES::dispatcher();
		$args = array(&$url, &$parser, &$contents, &$result);

		$dispatcher->trigger(SOCIAL_TYPE_USER, 'onAfterLinkCrawl' , $args);

		$result->url = $url;

		$this->normalizeResult($result);

		return $result;
	}

	public function normalizeResult(&$result)
	{
		if (!isset($result->images)) {
			$result->images = array();
		}

		// If there is an oembed title, we should use it instead
		if (isset($result->oembed->title)) {
			$result->title = $result->oembed->title;
		}

		// We should rely on the opengraph title if there is
		if (isset($result->opengraph->title)) {
			$result->title = $result->opengraph->title;
		}

		if (isset($result->opengraph->desc)) {
			$result->description = $result->opengraph->desc;
		}

		if (isset($result->oembed->description)) {
			$result->description = $result->oembed->description;
		}

		// Normalize the properties
		$result->title = isset($result->title) ? $result->title : $result->url;

		// If the oembed has a thumbnail, we should always use it as the first image
		if (isset($result->oembed->thumbnail)) {
			array_unshift($result->images, $result->oembed->thumbnail);
		}

		// If the page has opengraph data
		if (isset($result->opengraph->image)) {
			array_unshift($result->images, $result->opengraph->image);
		}

		// If opengraph has video
		if (isset($result->opengraph->video)) {
			$result->video = $result->opengraph->video;
		}

		if (!isset($result->video)) {
			$result->video = false;
		}
	}

	/**
	 * Invoke the crawling.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function crawl($url)
	{
		return $this->scrape($url);
	}

	/**
	 * Retrieves a list of hooks
	 *
	 * @since	1.3
	 * @access	public
	 */
	public function getHooks($url, $parser, $contents)
	{
		$files = JFolder::files(__DIR__ . '/hooks', '.php', false, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'abstract.php', 'oembed.php', 'charset.php'));

		if (!$files) {
			return false;
		}

		$hooks = array();

		foreach ($files as $file) {

			$path = __DIR__ . '/hooks/' . $file;

			require_once($path);

			// When item doesn't exist set it to false.
			$file = str_ireplace('.php', '', $file);
			$className = 'SocialCrawler' . ucfirst($file);

			if (!class_exists($className)) {
				continue;
			}

			$obj = new $className($url, $parser, $contents);

			$hooks[] = $obj;
		}

		return $hooks;
	}

	/**
	 * Loads adapters into the current namespace allowing the processing part to call these adapters.
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	private function parse($originalUrl , $url)
	{
		// Get the parser
		$parser	= SocialSimpleHTML::str_get_html($this->contents);

		if (!$parser) {
			return false;
		}

		$info = parse_url($url);
		$uri = $info['scheme'] . '://' . $info['host'];

		// Get the absolute url
		$absoluteUrl = $url;


		// Normalize the properties
		$result->url = $url;
		$result->title = isset($result->title) ? $result->title : $result->url;

		return true;
	}

	/**
	 * Retrieves the hooks values.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getData()
	{
		return $this->hooks;
	}
}
