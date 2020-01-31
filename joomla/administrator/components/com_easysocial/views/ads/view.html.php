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

class EasySocialViewAds extends EasySocialAdminView
{
	/**
	 * Main method to display the advertisements view.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function display($tpl = null)
	{
		$this->setHeading('COM_ES_HEADING_ADS');

		JToolbarHelper::addNew();
		JToolbarHelper::publishList();
		JToolbarHelper::unpublishList();
		JToolbarHelper::divider();
		JToolbarHelper::deleteList();

		// Default filters
		$options = array('initState' => true, 'namespace' => 'ads.listing');
		$model = ES::model('Ads', $options);
		$search = $model->getState('search');

		// Get the current ordering.
		$ordering = $this->input->get('ordering', $model->getState('ordering'), 'word');
		$direction = $this->input->get('direction', $model->getState('direction'), 'word');
		$state = $this->input->get('state', $model->getState('state'), 'default');
		$limit = $model->getState('limit');

		$ads = $model->getItemsWithState();

		// Get pagination
		$pagination = $model->getPagination();

		$this->set('limit', $limit);
		$this->set('search', $search);
		$this->set('ordering', $ordering);
		$this->set('direction', $direction);
		$this->set('state', $state);
		$this->set('ads', $ads);
		$this->set('pagination', $pagination);

		parent::display('admin/ads/default/default');
	}

	/**
	 * Main method to display the advertisements view.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function advertisers($tpl = null)
	{
		$this->setHeading('COM_ES_HEADING_ADVERTISERS');

		JToolbarHelper::addNew();
		JToolbarHelper::divider();
		JToolbarHelper::publishList('publishAdvertiser');
		JToolbarHelper::unpublishList('unpublishAdvertiser');
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('', 'deleteAdvertiser', JText::_('COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_DELETE'));

		// Default filters
		$options = array('initState' => true, 'namespace' => 'ads.listing');
		$model = ES::model('Advertisers', $options);
		$search = $model->getState('search');
		$callback = JRequest::getVar('callback', '');

		// Get the current ordering.
		$ordering = $this->input->get('ordering', $model->getState('ordering'), 'word');
		$direction = $this->input->get('direction', $model->getState('direction'), 'word');
		$state = $this->input->get('state', $model->getState('state'), 'default');
		$limit = $model->getState('limit');

		$advertisers = $model->getItemsWithState();

		// Get pagination
		$pagination = $model->getPagination();

		$this->set('limit', $limit);
		$this->set('search', $search);
		$this->set('ordering', $ordering);
		$this->set('direction', $direction);
		$this->set('state', $state);
		$this->set('advertisers', $advertisers);
		$this->set('pagination', $pagination);
		$this->set('callback', $callback);

		parent::display('admin/ads/advertisers/default');
	}

	/**
	 * Main method to display the form.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function form($tpl = null)
	{
		// Get all advertisers
		$model = ES::model('Advertisers');
		$advertisers = $model->getItems();

		if (!$advertisers) {
			$this->info->set(false, 'COM_ES_ADS_NO_ADVERTISER_CREATED_ON_THE_SITE', 'error');
			return $this->redirect('index.php?option=com_easysocial&view=ads&layout=advertisers');
		}

		// Get the id from the request.
		$id = $this->input->get('id', 0, 'int');

		// Get the table object
		$ad = ES::table('Ad');
		$state = $ad->load($id);

		// Add heading here.
		$this->setHeading('COM_ES_CREATE_NEW_AD');

		JToolbarHelper::apply('apply', JText::_('COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE'), false, false);
		JToolbarHelper::save('save', JText::_('COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_CLOSE'));
		JToolbarHelper::cancel('cancel', JText::_('COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_CANCEL'));

		if ($id) {
			$this->setHeading($ad->get('title'), 'COM_ES_DESCRIPTION_EDIT_AD');
		}

		$showLimit = true;

		if ($ad->start_date == '0000-00-00 00:00:00' || !$ad->id) {
			$ad->start_date = false;
			$ad->end_date = false;

			$showLimit = false;
		}

		// Default value for new ad
		if (!$ad->id) {
			$ad->state = true;
		}

		$this->set('ad', $ad);
		$this->set('advertisers', $advertisers);
		$this->set('showLimit', $showLimit);

		parent::display('admin/ads/form/default');
	}

	/**
	 * Main method to display the form.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function advertiserForm($tpl = null)
	{
		// Get the id from the request.
		$id = $this->input->get('id', 0, 'int');

		// Get the table object
		$advertiser = ES::table('Advertiser');
		$state = $advertiser->load($id);

		// Add heading here.
		$this->setHeading('COM_ES_CREATE_NEW_ADVERTISER');

		JToolbarHelper::apply('applyAdvertiser', JText::_('COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE'), false, false);
		JToolbarHelper::save('saveAdvertiser', JText::_('COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_CLOSE'));
		JToolbarHelper::cancel('cancel', JText::_('COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_CANCEL'));

		if ($id) {
			$this->setHeading($advertiser->get('name'), 'COM_ES_DESCRIPTION_EDIT_ADVERTISER');
		}

		// Default value for new ad
		if (!$advertiser->id) {
			$advertiser->state = true;
		}

		$this->set('advertiser', $advertiser);

		parent::display('admin/ads/advertisers/form');
	}

	/**
	 * Post process after an ad is deleted
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function remove($task = null, $ad = null)
	{
		return $this->redirect('index.php?option=com_easysocial&view=ads');
	}

	/**
	 * Post process after an advertiser is deleted
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function deleteAdvertiser($task = null, $ad = null)
	{
		return $this->redirect('index.php?option=com_easysocial&view=ads&layout=advertisers');
	}

	/**
	 * Post process after ads has been published / unpublished
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function togglePublish($task = null)
	{
		return $this->redirect('index.php?option=com_easysocial&view=ads');
	}

	/**
	 * Post process after an ad is stored
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function store($task = null, $ad = null)
	{
		$url = 'index.php?option=com_easysocial&view=ads';

		if ($task == 'apply' || $this->hasErrors()) {
			return $this->redirect($url . '&layout=form&id=' . $ad->id);
		}

		return $this->redirect($url);
	}

	/**
	 * Post process after an advertiser is stored
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function saveAdvertiser($task = null, $ad = null)
	{
		$url = 'index.php?option=com_easysocial&view=ads&layout=advertisers';

		if ($task == 'applyAdvertiser' || $this->hasErrors()) {
			return $this->redirect($url . '&layout=advertiserForm&id=' . $ad->id);
		}

		return $this->redirect($url);
	}

	/**
	 * Post action after publishing or unpublishing advertiser
	 *
	 * @since  3.0
	 * @access public
	 */
	public function togglePublishAdvertiser()
	{
		return $this->redirect(ESR::url(array('view' => 'ads', 'layout' => 'advertisers')));
	}
}
