<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

// Determine which type does the video belong to
if (isset($type)) {

	if ($type != SOCIAL_TYPE_USER) {
		$clustersType = array('group' => 'groups', 'event' => 'events', 'page' => 'pages');

		// lets further test if we need to add the cluster type or not.
		$addCluster = ESR::getMenus($clustersType[$type]) ? false : true;

		if ($addCluster) {
			$title[] = JString::ucwords(JText::_('COM_EASYSOCIAL_SH404_TYPE_' . strtoupper($type)));
		}
	}

	if (isset($uid)) {


		$alias = '';

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

// Add the view to the list of titles
if (isset($view)) {
	addView($title, $view, $menuView);
}

// Filters
if (isset($filter)) {

	$title[] = JString::ucwords(JText::_('COM_EASYSOCIAL_ROUTER_VIDEOS_FILTER_' . strtoupper($filter)));
	shRemoveFromGETVarsList('filter');
}

// Category alias
if (isset($categoryId)) {
	$title[] = getVideoCategoryAlias($categoryId);
	shRemoveFromGETVarsList('categoryId');
}

//custom filter
if (isset($hashtagFilterId) && $hashtagFilterId) {
	$title[] = JString::ucwords(JText::_('COM_EASYSOCIAL_ROUTER_VIDEOS_HASHTAG_FILTER'));
	$title[] = $hashtagFilterId;

	shRemoveFromGETVarsList('hashtagFilterId');
}

//hashtag
if (isset($hashtag) && $hashtag) {
	$title[] = JString::ucwords(JText::_('COM_EASYSOCIAL_ROUTER_VIDEOS_FILTER_HASHTAG'));
	$title[] = $hashtag;

	shRemoveFromGETVarsList('hashtag');
}

// Sorting
if (isset($sort)) {
	$title[] = JString::ucwords($sort);
	shRemoveFromGETVarsList('sort');
}

// For videos, we need to get the beautiful title
if (isset($id)) {

	// Get the video alias
	$alias = getVideoAlias($id);

	// Set the video alias
	$title[] = $alias;

	shRemoveFromGETVarsList('id');
}

// Layouts
if (isset($layout) && $layout != 'item') {
	addLayout($title, $view, $layout);
} else {
	shRemoveFromGETVarsList('layout');
}
