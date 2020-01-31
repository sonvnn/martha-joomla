<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class ThemesHelperHTML extends ThemesHelperAbstract
{
	/**
	 * Displays the stream's page title
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function anywhereTitle($stream)
	{
		$theme = ES::themes();

		$theme->set('pageTitle', $stream->getParams()->get('pageTitle'));
		$theme->set('pagePermalink', $stream->getPermalink());

		$output = $theme->output('admin/html/html.anywheretitle');

		return $output;
	}

	/**
	 * Renders a cluster permalink
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function cluster($object, $popbox = false, $popboxPosition = 'top-left', $permalink = false)
	{
		if (!is_object($object)) {
			return false;
		}

		// Sometimes we want to show different permalink such as edit link for admin.
		if (!$permalink) {
			$permalink = $object->getPermalink();
		}

		$theme = ES::themes();
		$theme->set('popbox', false);
		$theme->set('popboxPosition', $popboxPosition);
		$theme->set('cluster', $object);
		$theme->set('permalink', $permalink);

		$output = $theme->output('site/helpers/html/cluster');

		return $output;
	}

	/**
	 * Generates the empty block
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function emptyBlock($text, $icon, $withBorders = false, $isItem = true)
	{
		$text = JText::_($text);

		$theme = ES::themes();

		$theme->set('isItem', $isItem);
		$theme->set('withBorders', $withBorders);
		$theme->set('text', $text);
		$theme->set('icon', $icon);

		$output = $theme->output('site/helpers/html/empty');

		return $output;
	}

	/**
	 * Renders the login block
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function login($returnUrl = '', $moduleParams = false, $options = array())
	{
		// Default values
		$fields = false;

		// Get the facebook library
		$sso = ES::sso();
		$facebook = ES::oauth('Facebook');

		$lockdown = $this->config->get('general.site.lockdown.enabled');
		$showRegistrations = true;

		// If registration is enabled, display the quick registration form
		if (!$this->config->get('registrations.enabled') || ($lockdown && !$this->config->get('general.site.lockdown.registration'))) {
			$showRegistrations = false;
		}

		// If caller came from module, overwrite the setting
		if ($moduleParams) {
			$showRegistrations = $moduleParams->get('show_quick_registration', true);
		}

		// Only process mini registration form if mini registration is enabled
		if ($showRegistrations && $this->config->get('registrations.mini.enabled')) {
			$model = ES::model('Fields');
			$profileId = $this->config->get('registrations.mini.profile', 'default');

			if ($profileId === 'default') {
				$profileId = ES::model('Profiles')->getDefaultProfile()->id;
			}

			// If caller came from module, overwrite the setting
			if ($moduleParams) {
				$profileId = $moduleParams->get('profile_id', ES::model('Profiles')->getDefaultProfile()->id);
			}

			// Get a list of custom fields for quick registration
			$fields = $model->getQuickRegistrationFields($profileId);

			if (!empty($fields)) {
				ES::language()->loadAdmin();

				$lib = FD::fields();

				$session = JFactory::getSession();
				$registration = FD::table('Registration');
				$registration->load($session->getId());

				$data = $registration->getValues();
				$args = array(&$data, &$registration);

				$lib->trigger('onRegisterMini', SOCIAL_FIELDS_GROUP_USER, $fields, $args);
			}
		}

		$theme = ES::themes();
		$theme->set('showRegistrations', $showRegistrations);
		$theme->set('sso', $sso);
		$theme->set('fields', $fields);
		$theme->set('facebook', $facebook);
		$theme->set('returnUrl', $returnUrl);
		$theme->set('options', $options);

		$usernamePlaceholder = $this->config->get('general.site.loginemail') ? 'COM_EASYSOCIAL_LOGIN_USERNAME_OR_EMAIL_PLACEHOLDER' : 'COM_EASYSOCIAL_LOGIN_USERNAME_PLACEHOLDER';
		if ($this->config->get('registrations.emailasusername')) {
			$usernamePlaceholder = 'COM_EASYSOCIAL_LOGIN_EMAIL_PLACEHOLDER';
		}

		$theme->set('usernamePlaceholder', JText::_($usernamePlaceholder));

		$output = $theme->output('site/helpers/html/login');

		return $output;
	}

	/**
	 * Renders a loading indicator
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function loading($position = 'top')
	{
		$theme = ES::themes();
		$theme->set('position', $position);
		$output = $theme->output('site/helpers/html/loading');

		return $output;
	}

	/**
	 * Display header cover of an object
	 *
	 * @since	2.1
	 * @access	public
	 */
	public function headerCover($obj, $active = 'timeline')
	{
		$theme  = ES::themes();

		if (!$obj->id) {
			return;
		}

		$namespace = 'cover.user';

		if ($obj instanceof SocialGroup) {
			$namespace = 'cover.group';
		}

		if ($obj instanceof SocialPage) {
			$namespace = 'cover.page';
		}

		if ($obj instanceof SocialEvent) {
			$namespace = 'cover.event';
		}

		// Get current view as active
		if (!$active) {
			$active = $this->input->get('view', '', 'cmd');
		}

		return $theme->html($namespace, $obj, $active);
	}

	/**
	 * Displays the mini header of an object
	 * Deprecated. Use @headerCover instead.
	 *
	 * @deprecated 2.1
	 * @access	public
	 */
	public function miniheader($obj, $active = '')
	{
		// @deprecated. Use headerCover instead.
		return $this->headerCover($obj, $active);

		$theme = FD::themes();
		$output = '';

		if (!$obj->id) {
			return;
		}

		if ($obj instanceof SocialUser) {

			// We should hide mini header on mobile devices #525
			if ($theme->isMobile()) {
				return;
			}

			$showDropdown = true;

			$canBlockUser = !$this->my->guest && $this->my->id != $obj->id && !$obj->isSiteAdmin() && $this->config->get('users.blocking.enabled');
			$canReportUser = ES::reports()->canReport();
			$canBanUser = $this->my->canBanUser($obj);
			$canDeleteUser = $this->my->canDeleteUser($obj);

			if ($obj->isViewer()) {
				$canReportUser = false;
			}

			$showDropdown = $canBlockUser || $canReportUser || $canBanUser || $canDeleteUser;

			$theme->set('canBanUser', $canBanUser);
			$theme->set('canReportUser', $canReportUser);
			$theme->set('canDeleteUser', $canDeleteUser);
			$theme->set('canBlockUser', $canBlockUser);
			$theme->set('showDropdown', $showDropdown);
			$theme->set('user', $obj);

			$output = $theme->output('site/helpers/user/mini.header');
		}

		if ($obj instanceof SocialGroup) {
			$theme->set('group', $obj);
			$output = $theme->output('site/helpers/group/mini.header');
		}

		if ($obj instanceof SocialEvent) {

			$theme->set('event', $obj);
			$output = $theme->output('site/helpers/event/mini.header');
		}

		if ($obj instanceof SocialPage) {

			$theme->set('page', $obj);
			$output = $theme->output('site/helpers/page/mini.header');
		}

		return $output;
	}

	/**
	 * Renders the online state of a user
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function online($online = false, $size = 'small')
	{
		$theme = ES::themes();

		$theme->set('online', $online);
		$theme->set('size', $size);

		$output = $theme->output('site/utilities/user.online.state');

		return $output;
	}

	/**
	 * Displays the video's title in html format
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function video(SocialVideo $video)
	{
		$theme = ES::themes();

		$theme->set('video', $video);

		$output = $theme->output('admin/html/html.video');

		return $output;
	}

	/**
	 * Displays the audio's title in html format
	 *
	 * @since	2.1
	 * @access	public
	 */
	public function audio(SocialAudio $audio)
	{
		$theme = ES::themes();

		$theme->set('audio', $audio);

		$output = $theme->output('admin/html/html.audio');

		return $output;
	}

	/**
	 * Renders a progress bar for registration and creation
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function steps($steps, $currentStep, $startOptions = array(), $endOptions = array())
	{
		$firstStepLink = 'javascript:void(0);';
		$firstStepTooltip = '';

		$lastStepLink = 'javascript:void(0);';
		$lastStepTooltip = '';

		if ($startOptions) {

			if (isset($startOptions['link'])) {
				$firstStepLink = $startOptions['link'];
			}

			if (isset($startOptions['tooltip'])) {
				$firstStepTooltip = JText::_($startOptions['tooltip'], true);
			}
		}

		if ($endOptions) {
			if (isset($endOptions['tooltip'])) {
				$lastStepTooltip = JText::_($endOptions['tooltip'], true);
			}
		}

		$theme = ES::themes();
		$theme->set('steps', $steps);
		$theme->set('currentStep', $currentStep);
		$theme->set('firstStepTooltip', $firstStepTooltip);
		$theme->set('firstStepLink', $firstStepLink);
		$theme->set('lastStepLink', $lastStepLink);
		$theme->set('lastStepTooltip', $lastStepTooltip);

		$output = $theme->output('site/helpers/html/steps');

		return $output;
	}

	/**
	 * Renders the snackbar title used across EasySocial
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function snackbar($text, $heading = 'h2')
	{
		$theme = ES::themes();

		$text = JText::_($text);
		$theme->set('heading', $heading);
		$theme->set('text', $text);
		$output = $theme->output('site/helpers/html/snackbar');

		return $output;
	}

	/**
	 * Renders the es-sidebar module position
	 *
	 * @since	3.0.4
	 * @access	public
	 */
	public function sidebar($view = '')
	{
		$view = $this->input->get('view', '', 'cmd');
		$layout = $this->input->get('layout', '', 'cmd');

		// the reason need to add another one for the editnotiifcations is because menu link already set wrong for this edit notification menu item
		$excludeProfileLayout = array('editNotifications', 'editnotifications', 'editPrivacy');

		// we should skip this if the user profile sidebar set to hidden
		if ($view == 'profile' && $this->config->get('users.profile.sidebar') == 'hidden' && !in_array($layout, $excludeProfileLayout)) {
			return;
		}

		$theme = ES::themes();
		$output = $theme->output('site/helpers/html/sidebar');

		return $output;
	}

	/**
	 * Displays the author's and cluster's name in html format
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function user($id, $popbox = false, $popboxPosition = 'top-left', $avatar = false, $class = '', $userIcon = false)
	{
		if (is_object($id)) {
			$user = $id;
		} else {
			$user = ES::user($id);
		}

		// Some html.user caller is passing page due to postAs feature
		if ($user->getType() == SOCIAL_TYPE_PAGE) {
			return $this->cluster($user, $popbox, $popboxPosition);
		}

		if ($user instanceof SocialUser) {
			if ($user->block) {
				return $user->getName();
			}
		}

		if ($avatar) {
			$class .= ' es-avatar';
		}

		// Determines if we should apply custom colors
		$profile = $user->getProfile();
		$profileParams = $profile->getParams();

		$customStyle = '';
		$styles = array();

		if ($profileParams->get('label_colour') && $profileParams->get('label_font_colour')) {
			$styles[] = 'color: ' . $profileParams->get('label_font_colour');
		}

		if ($profileParams->get('label_background') && $profileParams->get('label_background_colour')) {
			$styles[] = 'background: ' . $profileParams->get('label_background_colour');
			$styles[] = 'padding: 1px';
		}

		if ($styles) {
			$customStyle = implode(';', $styles);
		}

		$theme = ES::themes();
		$theme->set('customStyle', $customStyle);
		$theme->set('userIcon', $userIcon);
		$theme->set('popbox', $popbox);
		$theme->set('avatar', $avatar);
		$theme->set('position', $popboxPosition);
		$theme->set('user', $user);
		$theme->set('class', $class);

		$output = $theme->output('admin/html/html.user');

		return $output;
	}

	/**
	 * Deprecated. Use @cluster instead
	 *
	 * @deprecated	2.0
	 */
	public function event($obj, $popbox = false, $popboxPosition = 'top-left')
	{
		if (!is_object($obj)) {
			$obj = ES::event($obj);
		}

		return $this->cluster($obj, $popbox, $popboxPosition);
	}

	/**
	 * Deprecated. Use @cluster instead
	 *
	 * @deprecated	2.0
	 */
	public function group($obj, $popbox = false, $popboxPosition = 'top-left')
	{
		if (!is_object($obj)) {
			$obj = ES::group($obj);
		}

		return $this->cluster($obj, $popbox, $popboxPosition);
	}


	/**
	 * Deprecated. Use @cluster instead
	 *
	 * @deprecated	2.0
	 */
	public function page($obj, $popbox = false, $popboxPosition = 'top-left')
	{
		if (!is_object($obj)) {
			$obj = ES::page($obj);
		}

		return $this->cluster($obj, $popbox, $popboxPosition);
	}

	/**
	 * Renders a map popbox
	 *
	 * @since	1.4
	 * @access	public
	 */
	public function map(SocialLocation $location, $displayIcon = false)
	{
		$theme = ES::themes();

		$latitude = $location->getLatitude();
		$longitude = $location->getLongitude();
		$address = $location->getAddress();
		$mapUrl = $location->getMapUrl();

		$theme->set('displayIcon', $displayIcon);
		$theme->set('latitude', $latitude);
		$theme->set('longitude', $longitude);
		$theme->set('address', $address);
		$theme->set('mapUrl', $mapUrl);

		$output = $theme->output('admin/html/html.map');

		return $output;
	}

	/**
	 * Renders a standard restricted box
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function restricted($title, $content, $icon = true, $customHtml = '')
	{
		$theme = ES::themes();
		$theme->set('icon', $icon);
		$theme->set('title', $title);
		$theme->set('content', $content);
		$theme->set('customHtml', $customHtml);

		$output = $theme->output('site/helpers/html/restricted');

		return $output;
	}
}
