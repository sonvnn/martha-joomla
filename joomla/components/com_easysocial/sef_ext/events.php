<?php
/**
* @package    EasySocial
* @copyright  Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

// Determine how is the user's current id being set.
if (isset($userid)) {
	$title[] = getUserAlias($userid);

	shRemoveFromGETVarsList( 'userid' );

	if (isEasysocialURLPluginEnabled()) {
		$menuView = '';
	}
}


if (isset($type)) {

	if ($type != SOCIAL_TYPE_USER) {
		if (!in_array($menuView, array('groups', 'events', 'pages'))) {
			$title[] = JString::ucwords(JText::_('COM_EASYSOCIAL_SH404_TYPE_' . strtoupper($type)));
		}
	}

	if (isset($uid)) {

		if ($type == SOCIAL_TYPE_USER) {
			$alias = getUserAlias($uid);
		}

		if ($type == SOCIAL_TYPE_GROUP) {
			$alias = getGroupAlias($uid);
		}

		if ($type == SOCIAL_TYPE_EVENT) {
			$alias = getEventAlias($uid);
		}

		if ($type == SOCIAL_TYPE_PAGE) {
			$alias = getPageAlias($uid);
		}


		$title[] = $alias;

		shRemoveFromGETVarsList('uid');
	}
	shRemoveFromGETVarsList('type');

	if (isEasysocialURLPluginEnabled()) {
		$menuView = '';
	}
}


if (isset($view)) {
	addView($title, $view, $menuView);
}

if (isset($id) && isset($view) && $view == 'events' && isset($layout) && $layout == 'item') {
	$title[] = getEventAlias($id);

	shRemoveFromGETVarsList('id');
}

if (isset($layout) && $layout != 'item') {
	addLayout($title, $view, $layout, $Itemid);
} else {
	shRemoveFromGETVarsList('layout');
}


if (isset($categoryid)) {
	$title[] = getEventCategoryAlias($categoryid);

	shRemoveFromGETVarsList('categoryid');
}

if (isset($id) && isset($view) && $view == 'events' && isset($layout) && $layout == 'category') {
	$category = FD::table('EventCategory');
	$category->load($id);

	$alias = $category->getAlias();
	$alias = str_ireplace(':', '-', $alias);

	$title[] = $alias;

	shRemoveFromGETVarsList('id');
}

if (isset($appId) && $appId && $view == 'events' && $layout == 'item') {

	$title[] = getAppAlias($appId);

	shRemoveFromGETVarsList('appId');
}

if (isset($filter) && $filter) {

	$title[]    = JString::ucwords(JText::_('COM_EASYSOCIAL_ROUTER_EVENTS_FILTER_' . strtoupper($filter)));
	shRemoveFromGETVarsList('filter');
}

// Sorting
if (isset($ordering) && $ordering) {
	$title[] = JString::ucwords($ordering);
	shRemoveFromGETVarsList('ordering');
}
