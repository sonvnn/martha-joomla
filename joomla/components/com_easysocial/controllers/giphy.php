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

class EasySocialControllerGiphy extends EasySocialController
{
	/**
	 * Search for giphy via query
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function search()
	{
		ES::requireLogin();
		
		// Get the search query input
		$query = $this->input->get('query', '', 'string');

		// Determine whether it is coming from the story form or not
		$from = $this->input->get('from', '', 'string');

		// Search and get the data
		$data = ES::giphy()->getData($query);

		$data = $data ? $data : false;
	
		return $this->view->call(__FUNCTION__, $data);
	}
}
