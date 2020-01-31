<?php
/*------------------------------------------------------------------------

# Grid Gallery Addon

# ------------------------------------------------------------------------

# author    Sonny

# copyright Copyright (C) 2019 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

if($controller = TZ_Portfolio_Plus_AddOnControllerLegacy::getInstance('TZ_Portfolio_Plus_AddOn_Grid_Gallery'
	, array('base_path' => COM_TZ_PORTFOLIO_PLUS_ADDON_PATH
		.DIRECTORY_SEPARATOR.'mediatype'
		.DIRECTORY_SEPARATOR.'grid_gallery'.DIRECTORY_SEPARATOR.'admin'))) {
	$task   = JFactory::getApplication()->input->get('addon_task');
	$controller->execute($task);
	$controller->redirect();
}