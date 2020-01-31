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

class EasySocialControllerFields extends EasySocialController
{
	/**
	 * Retrieves a list of custom fields on the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getFields()
	{
		$lib 		= FD::fields();

		// TODO: Enforce that group be a type of user , groups only.
		$group 		= JRequest::getWord( 'group' , SOCIAL_FIELDS_GROUP_USER );

		// Get a list of fields
		$model 		= FD::model( 'Apps' );
		$fields 	= $model->getApps( array( 'type' => SOCIAL_APPS_TYPE_FIELDS ) );

		// We might need this? Not sure.
		$data = array();

		// Once done, pass this back to the view.
		$view 		= FD::getInstance( 'View' , 'Fields' );
		$view->call( __FUNCTION__ , $fields );
	}

	/**
	 * Render's field configuration.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function renderConfiguration()
	{
		// Check for request forgeries
		ES::checkToken();

		// Get the application id.
		$appId = $this->input->get('appid', 0, 'int');

		// Get the field id. If this is empty, it is a new field item that's being added to the form.
		$fieldId = $this->input->get('fieldid', 0, 'int');

		// Application id should never be empty.
		if (!$appId) {
			$this->view->setMessage('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_INVALID_APP_ID_PROVIDED', ES_ERROR);

			return $this->view->call(__FUNCTION__);
		}

		// Load frontend's language file
		ES::language()->loadSite();

		$fields = ES::fields();

		// getFieldConfigParameters is returning a stdClass object due to deep level data
		$config = $fields->getFieldConfigParameters($appId, true);

		// getFieldConfigValues is returning a JRegistry object
		$params = $fields->getFieldConfigValues($appId, $fieldId);

		// Get the html content
		$html = $fields->getConfigHtml($appId, $fieldId);

		return $this->view->call(__FUNCTION__, $config, $params, $html);
	}
}
