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

require_once(__DIR__ . '/abstract.php');

class SocialSidebarPhotos extends SocialSidebarAbstract
{
	/**
	 * Renders the output from the sidebar
	 *
	 * @since	3.0.0
	 * @access	public
	 */
	public function render()
	{
		// $helper = ES::viewHelper('Albums', 'List');
		$layout = $this->input->get('layout', '', 'cmd');

		if ($layout != 'item' && $layout != 'form') {
			return false;
		}

		return $this->renderItem();
	}

	/**
	 * Render sidebar for albums page
	 *
	 * @since	3.0.0
	 * @access	public
	 */
	public function renderItem()
	{
		$helper = ES::viewHelper('Photos', 'Item');

		$lib = $helper->getLib();
		$id = $helper->getId();
		$photos = $helper->getPhotos();
		$total = $helper->getTotal();
		$album = $helper->getAlbum();
		$limit = $helper->getLimit();

		$current = 0;

		if ($photos && is_array($photos)) {
			$current = count($photos);
		}

		$path = $this->getTemplatePath('photo_item');
		require($path);
	}
}
