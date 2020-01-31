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

class SocialTranslationsAzure extends EasySocial
{  
	public $key = '';
	public $endPoint = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/';
	public $scopeUrl = 'http://api.microsofttranslator.com';
	public $grantType = 'client_credentials';

	public function __construct()
	{
		parent::__construct();

		$this->key = $this->config->get('stream.translations.azurekey');
	}

	/**
	 * Exchange the token with a valid key
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getToken()
	{
		$auth = new SocialTokenAuthentication();
		$token = $auth->getToken($this->key);

		return $token;
	}

	/**
	 * Translates a given content to a language
	 *
	 * @since   1.4
	 * @access  public
	 */
	public function translate($contents, $targetLanguage)
	{
		$token = $this->getToken();
		
		$connector = ES::connector();
		
		// Set the url
		$url = 'http://api.microsofttranslator.com/v2/Http.svc/Translate?to=' . $targetLanguage . '&text=' . urlencode($contents);
		$connector->addUrl($url);
		
		// Add the token to the headers
		$headers = array('Authorization: ' . $token, 'Content-Type: text/xml');
		$connector->addHeader($headers);

		$connector->connect();

		$response = $connector->getResult($url);

		//Interprets a string of XML into an object.
		$xmlObj = simplexml_load_string($response);

		$output = '';

		$tmp = (array) $xmlObj[0];
		$keys = array_keys($tmp);

		// If there is a <body> in the response, we know something went wrong, just return the original contents
		if (isset($keys[0]) && $keys[0] === 'body') {
			return $contents;
		}

		foreach ((array)$xmlObj[0] as $val) {    
			$output = $val;
		}

		return $output;
	}
}

class SocialTokenAuthentication
{
	public $url = 'https://api.cognitive.microsoft.com/sts/v1.0/issueToken';

	/**
	 * Exchanges a key with Azure to get the token
	 *
	 * @since	2.0.8
	 * @access	public
	 */
	public function getToken($key)
	{
		$connector = ES::connector();

		$url = $this->url . '?Subscription-Key=' . $key;
		
		// Set the arguments for the request
		$connector->addUrl($url);

		// // Set it as a post request
		$connector->setMethod('POST');
		// $connector->setMethod('POST');

		// Try to connect and get the response
		@$connector->connect();

		$token = $connector->getResult($url);

		$response = json_decode($token);

		// If there's an error, skip this
		if (isset($response->error) && $response->error) {
			return false;
		}

		return 'Bearer ' . $token;
	}
}