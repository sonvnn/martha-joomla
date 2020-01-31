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

class DiscussionsWidgetsGroups extends SocialAppsWidgets
{
	/**
	 * Renders the menu link to start a new discussion
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function groupAdminStart($group)
	{
		if (!$group->getParams()->get('discussions', true)) {
			return;
		}

		$access = $group->getAccess();

		if (!$access->get('discussions.enabled', true)) {
			return false;
		}

		// Generate the permalink
		$permalink = ESR::apps(array('layout' => 'canvas', 'customView' => 'create', 'uid' => $group->getAlias(), 'type' => SOCIAL_TYPE_GROUP, 'id' => $this->app->getAlias()));

		$theme = ES::themes();
		$theme->set('permalink', $permalink);

		$output = $theme->output('themes:/site/discussions/widgets/menu');

		echo $output;
	}

	/**
	 * Generates the group statistics for discussions
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function groupStatsEnd($group)
	{
		if (!$group->getParams()->get('discussions', true)) {
			return;
		}

		$access = $group->getAccess();

		if (!$access->get('discussions.enabled', true)) {
			return false;
		}

		$model = ES::model('Discussions');
		$total = $model->getTotalDiscussions($group->id, SOCIAL_TYPE_GROUP);
		$permalink = $group->getAppPermalink('discussions');

		$theme = ES::themes();
		$theme->set('permalink', $permalink);
		$theme->set('total', $total);

		$output = $theme->output('themes:/site/discussions/widgets/stats');

		echo $output;
	}

	/**
	 * Renders the recent discussion widget
	 *
	 * @since	2.2
	 * @access	public
	 */
	public function sidebarBottom($id)
	{
		// Set the max length of the item
		$params = $this->app->getParams();
		$enabled = $params->get('widget', true);

		$cluster = ES::cluster(SOCIAL_TYPE_GROUP, $id);

		if (!$enabled || !$this->app->hasAccess($cluster->category_id)) {
			return;
		}

		$theme = ES::themes();
		$options = array('limit' => (int) $params->get('widgets_total', 5));

		$model = ES::model('Discussions');
		$discussions = $model->getDiscussions($cluster->id, SOCIAL_TYPE_GROUP, $options);

		if (!$discussions) {
			return;
		}

		$theme->set('cluster', $cluster);
		$theme->set('discussions', $discussions);

		echo $theme->output('themes:/site/discussions/widgets/list');
	}
}
