<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

ES::import('admin:/includes/maintenance/dependencies');

class SocialMaintenanceScriptRemoveUnusedFiles extends SocialMaintenanceScript
{
	public static $title = 'Remove unused files from previous version';
	public static $description = 'Remove unused files from previous version.';

	public function main()
	{
		$files = array(
				SOCIAL_ADMIN_THEMES . '/default/settings/form/pages/general/login.php',
				SOCIAL_ADMIN_THEMES . '/default/settings/form/pages/stream/pagination.php',
				SOCIAL_ADMIN_DEFAULTS . '/sidebar/access.json',
				SOCIAL_ADMIN_DEFAULTS . '/sidebar/maintenance.json',
				SOCIAL_ADMIN_DEFAULTS . '/sidebar/profiles.json'
				);

		foreach ($files as $file) {
			if (JFile::exists($file)) {
				JFile::delete($file);
			}
		}

		return true;
	}
}
