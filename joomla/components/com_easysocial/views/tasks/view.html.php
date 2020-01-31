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

class EasySocialViewTasks extends EasySocialSiteView
{
	/**
	 * Post processing after saving a new milestone
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function saveMilestone(SocialCluster $cluster, $milestone, $redirect)
	{
		return $this->redirect($redirect);
	}

	/**
	 * Post processing after deleting a milestone
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function deleteMilestone()
	{
		$return = $this->getReturnUrl();

		return $this->app->redirect($return);
	}
}
