<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

ES::import('admin:/includes/views');

abstract class EasySocialAdminView extends EasySocialView
{
	protected $page = null;

	public function __construct($config = array())
	{
		// Initialize page.
		$page = new stdClass();

		// Initialize page values.
		$page->icon = '';
		$page->iconUrl = '';
		$page->heading = '';
		$page->description = '';

		$this->page = $page;
		$this->my = ES::user();
		$this->showSidebar = true;

		// Initialize the breadcrumbs
		$this->breadcrumbs	= array();

		$view = $this->getName();

		// Disallow access if user does not have sufficient permissions
		$rule = 'easysocial.access.' . $view;

		// For fields, it uses a different view
		if ($view == 'fields') {
			$rule 	= 'easysocial.access.profiles';
		}

		// Fix for videocategories view.
		if ($view == 'videocategories') {
			$rule = 'easysocial.access.videos';
		}

		if (!$this->authorise($rule)) {
			$this->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}

		// Since J2.5 have it own class for the toolbar "remove featured", we need to use it.
		$this->removeFeaturedIcon = 'star';
		if (ES::version()->version[0] == '2') {
			$this->removeFeaturedIcon = 'featured toolbar-inactive';
		}

		// load frontend languague file
		ES::language()->loadSite(); #1624

		parent::__construct($config);
	}

	/**
	 * Checks for user access
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function authorise($command, $extension = 'com_easysocial')
	{
		return $this->my->authorise($command, $extension);
	}

	/**
	 * Allows caller to set the header title in the structure layout.
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function setHeading($title, $description = '')
	{
		if ($description) {
			$this->page->description = JText::_($description);
		} else {
			$this->page->description = JText::_($title . '_DESC');
		}

		$this->page->heading = JText::_($title);
	}

	/**
	 * Allows caller to set the header title in the structure layout.
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function setDescription($description)
	{
		$this->page->description = JText::_($description);
	}

	/**
	 * Central method that is called by child items to display the output.
	 * All views that inherit from this class should use display to output the html codes.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function display($tpl = null)
	{
		$format = $this->doc->getType();
		$tmpl = $this->input->get('tmpl', '', 'string');
		$view = $this->input->get('view', '', 'string');

		// Joomla page title should always display EasySocial
		JToolbarHelper::title(JText::_('COM_EASYSOCIAL_TITLE_EASYSOCIAL'), 'easysocial');

		if ($format == 'html') {
			// Load Joomla's framework.
			JHTML::_('behavior.framework');

			$class = '';

			if ($tmpl == 'component') {
				$class 	= 'es-window';
			}


			// Check for welcome message
			ES::checkSEFCacheMessage();


			// Main wrapper
			$class = isset($class) ? $class : '';

			// Add the sidebar to the page obj.
			$sidebar = $this->getSideBar();
			$message = ES::getMessageQueue();
			// Capture contents.
			ob_start();
			parent::display($tpl);
			$html = ob_get_contents();
			ob_end_clean();

			$version = ES::getLocalVersion();

			// get media privacy unsyned counts.
			$privacyUnsynedCount = 0;
			if ($view != 'maintenance') {
				$model = ES::model('Maintenance');
				$privacyUnsynedCount = $model->getMediaPrivacyCounts();
			}

			$theme = ES::themes();

			$theme->set('showSidebar', $this->showSidebar);
			$theme->set('version', $version);
			$theme->set('class', $class);
			$theme->set('tmpl', $tmpl);
			$theme->set('html', $html);
			$theme->set('message', $message);
			$theme->set('sidebar', $sidebar);
			$theme->set('page', $this->page);
			$theme->set('privacyUnsynedCount', $privacyUnsynedCount);
			$theme->set('customAction', $this->getCustomAction());

			$contents = $theme->output('admin/structure/default');

			echo $contents;

			return;
		}

		return parent::display($tpl);
	}

	/**
	 * Get custom action
	 *
	 * @since	2.1
	 * @access	public
	 */
	public function getCustomAction()
	{
		if (isset($this->page->customAction) && $this->page->customAction) {
			return $this->page->customAction;
		}
	}

	/**
	 * Set custom action to be display on the header
	 *
	 * @since	2.1
	 * @access	public
	 */
	public function setCustomAction($html)
	{
		$this->page->customAction = $html;
	}

	/**
	 * Allows overriden objects to redirect the current request only when in html mode.
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function redirect($uri, $message = '', $class = '')
	{
		$app = JFactory::getApplication();
		return $app->redirect($uri, $message, $class);
	}

	/**
	 * Standardize way of determining an active tab
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getActiveTab()
	{
		$tab = $this->input->get('activeTab', '', 'cmd');

		if (!$tab) {
			return;
		}

		$str = '&activeTab=' . $tab;

		return $str;
	}

	/**
	 * Returns the sidebar html codes.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getSideBar()
	{
		$showSidebar = JRequest::getVar('sidebar', 1);
		$showSidebar = $showSidebar == 1 ? true : false;

		if (!$showSidebar) {
			return;
		}

		$sidebar = ES::sidebar();
		$view = JRequest::getCmd( 'view' , 'easysocial' );
		$output = $sidebar->render($view);

		return $output;
	}
	/**
	 * Checks if the current viewer can really access this section
	 *
	 * @since	1.0.0
	 * @access	public
	 */
	public function checkAccess($rule)
	{
		if (!$this->my->authorise($rule , 'com_easysocial')) {
			ED::setMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			return $this->app->redirect('index.php?option=com_easysocial');
		}
	}

	/**
	 * This method should be invoked to discover new acl rules
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function discoveracl()
	{
		$paths = $this->config->get('access.paths');
		$model = ES::model('accessrules');
		$files = array();

		foreach ($paths as $path) {
			$result = $model->scan($path);

			$files = array_merge($files, $result);
		}

		foreach ($files as $file) {
			$model->install($file);
		}

		return $this->redirect('index.php?option=com_easysocial');
	}
}
