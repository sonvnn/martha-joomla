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

ES::import('admin:/tables/table');

class SocialTableWorkflowMap extends SocialTable
{
	public $id = null;
	public $uid = null;
	public $workflow_id = null;
	public $type = null;

	public function __construct(& $db)
	{
		parent::__construct('#__social_workflows_maps', 'id', $db);
	}
}