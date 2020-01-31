<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.file');

// Include main engine
$engine = JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/easysocial.php';
$exists = JFile::exists($engine);

if (!$exists) {
	return;
}

ES::initialize();

// Include the engine file.
require_once($engine);

$my = ES::user();

// If user is already logged in, there's no point to show the login form.
if (!$params->get('show_logout_button', true) && $my->id) {
	return;
}

// Load up the module engine
$lib = ES::modules($module, true);
$config = ES::config();
$sso = ES::sso();
$return = $lib->getLoginReturnUrl();

require($lib->getLayout());
