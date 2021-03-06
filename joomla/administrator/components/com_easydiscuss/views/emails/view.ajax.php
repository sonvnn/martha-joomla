<?php
/**
* @package		EasyDiscuss
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

require_once DISCUSS_ADMIN_ROOT . '/views/views.php';

class EasyDiscussViewSpools extends EasyDiscussAdminView
{
	/**
	 * Previews an email
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function preview()
	{
		$id = $this->input->get('id', 0, 'int');

		if (!$id) {
			return $this->ajax->reject();
		}

		$mailq = ED::table('Mailqueue');
		$mailq->load($id);
		
		$url = JURI::root() . 'administrator/index.php?option=com_easydiscuss&view=spools&layout=preview&tmpl=component&id=' . $mailq->id;		

		$theme = ED::themes();
		$theme->set('url', $url);

		$output = $theme->output('admin/spools/dialog.preview');

		return $this->ajax->resolve($output);
	}
}
