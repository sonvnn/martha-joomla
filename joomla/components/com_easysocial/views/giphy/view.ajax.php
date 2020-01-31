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

class EasySocialViewGiphy extends EasySocialSiteView
{
	/**
	 * Search for giphy via query
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function getForm()
	{
		ES::requireLogin();

		// Determine whether it is coming from the story form or not
		$from = $this->input->get('from', '', 'string');

		$story = false;

		if ($from == 'story') {
			$story = true;
		}

		// Search and get the data
		$giphies = ES::giphy()->getData();

		$theme = ES::themes();
		$theme->set('giphies', $giphies);
		$theme->set('story', $story);

		$html = $theme->output('site/giphy/browser/default');

		return $this->ajax->resolve($html);
	}

	/**
	 * Post process of search for giphy via query
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function search($data)
	{
		ES::requireLogin();

		$theme = ES::themes();
		$theme->set('giphies', $data);

		$html = $theme->output('site/giphy/browser/list');

		return $this->ajax->resolve($html);
	}

}
