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

ES::import('admin:/tables/clustercategory');

class SocialTablePageCategory extends SocialTableClusterCategory
{
	/**
	 * Override parent's store behavior
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function store($updateNulls = null)
	{
		$this->type = SOCIAL_TYPE_PAGE;

		// Save the current category first.
		return parent::store($updateNulls);
	}

	/**
	 * Retrieves total pages in this category
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function getTotalPages($options = array())
	{
		static $total = array();

		$defaultOptions = array(
			'state' => SOCIAL_STATE_PUBLISHED,
			'type' => array(SOCIAL_PAGES_PUBLIC_TYPE, SOCIAL_PAGES_PUBLIC_TYPE),
			'category_id' => $this->id
		);

		if ($this->container) {
			// Get all child ids from this category
			$model = ES::model('ClusterCategory');
			$childs = $model->getChildCategories($this->id, array(), SOCIAL_TYPE_PAGE, array('state' => SOCIAL_STATE_PUBLISHED));

			$childIds = array();

			foreach ($childs as $child) {
				$childIds[] = $child->id;
			}

			if (!empty($childIds)) {
				$defaultOptions['category_id'] = $childIds;
			}
		}

		$options = array_merge($defaultOptions, $options);

		ksort($options);

		$key = serialize($options);

		if (!isset($total[$this->id][$key])) {
			$total[$this->id][$key] = ES::model('Pages')->getTotalPages($options);
		}

		return $total[$this->id][$key];
	}
}
