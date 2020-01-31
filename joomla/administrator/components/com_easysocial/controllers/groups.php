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

class EasySocialControllerGroups extends EasySocialController
{
	public function __construct()
	{
		parent::__construct();

		// Map the alias methods here.
		$this->registerTask('unpublishCategory', 'togglePublishCategory');
		$this->registerTask('publishCategory', 'togglePublishCategory');

		$this->registerTask('publish', 'togglePublish');
		$this->registerTask('unpublish', 'togglePublish');

		$this->registerTask('applyCategory', 'saveCategory');
		$this->registerTask('saveCategoryNew', 'saveCategory');
		$this->registerTask('saveCategory', 'saveCategory');
		$this->registerTask('saveCategoryCopy', 'saveCategory');

		$this->registerTask('apply', 'store');
		$this->registerTask('save', 'store');
		$this->registerTask('savenew', 'store');
		$this->registerTask('savecopy', 'store');

		$this->registerTask('makeFeatured', 'toggleDefault');
		$this->registerTask('removeFeatured', 'toggleDefault');
	}

	/**
	 * Saves a group
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function store()
	{
		ES::checkToken();
		ES::language()->loadSite();

		$task = $this->getTask();
		$id = $this->input->get('id', 0, 'int');
		$cid = $id;

		// Flag to see if this is new or edit
		$isNew = empty($id);

		$isCopy = $task == 'savecopy' ? true : false;

		// Get the posted data
		$post = $this->input->getArray('post');
		$options = array();

		if ($isNew || $isCopy) {
			ES::import('admin:/includes/group/group');

			$group = new SocialGroup();
			$categoryId = $this->input->get('category_id', 0, 'int');

			if ($isCopy) {
				$cgroup = ES::group($id);
				$categoryId = $cgroup->category_id;

				// lets unset the id here.
				$post['id'] = 0;
			}
		} else {
			$group = ES::group($id);

			$options['data'] = true;
			$options['dataId'] = $group->id;
			$options['dataType'] = SOCIAL_FIELDS_GROUP_GROUP;
			$categoryId = $group->category_id;
		}

		$category = ES::table('GroupCategory');
		$category->load($categoryId);

		// Set the necessary data
		// $options['uid'] = $categoryId;
		$options['workflow_id'] = $category->getWorkflow()->id;
		$options['group'] = SOCIAL_FIELDS_GROUP_GROUP;

		// Get fields model
		$fieldsModel = ES::model('Fields');

		// Get the custom fields
		$fields = $fieldsModel->getCustomFields($options);

		// Initialize default registry
		$registry = ES::registry();

		// Get disallowed keys so we wont get wrong values.
		$disallowed = array(ES::token(), 'option' , 'task' , 'controller', 'autoapproval');

		// Process $_POST vars
		foreach ($post as $key => $value) {

			if (!in_array($key, $disallowed)) {

				if (is_array($value)) {
					$value = json_encode($value);
				}

				$registry->set($key, $value);
			}
		}

		// Convert the values into an array.
		$data = $registry->toArray();

		// Get the fields lib
		$fieldsLib = ES::fields();

		// Build arguments to be passed to the field apps.
		$args = array(&$data, &$group, &$isCopy);

		// @trigger onAdminEditValidate
		$errors = $fieldsLib->trigger('onAdminEditValidate', $options['group'], $fields, $args);

		// If there are errors, we should be exiting here.
		if (is_array($errors) && count($errors) > 0) {
			JRequest::set($data, 'post');

			$this->view->setMessage('COM_EASYSOCIAL_GROUPS_FORM_SAVE_ERRORS', ES_ERROR);
			return $this->view->call('form', $errors);
		}

		// @trigger onAdminEditBeforeSave
		$errors = $fieldsLib->trigger('onAdminEditBeforeSave', $options['group'], $fields, $args);

		// If there are errors, we should be exiting here.
		if (is_array($errors) && count($errors) > 0) {
			JRequest::set($data, 'post');

			$this->view->setMessage('COM_EASYSOCIAL_GROUPS_FORM_SAVE_ERRORS', ES_ERROR);
			return $this->view->call('form', $errors);
		}

		// Initialise group data for new group
		if ($isNew || $isCopy) {
			// Set the category id for the group
			$group->category_id = $categoryId;
			$group->creator_uid = $this->my->id;
			$group->creator_type = SOCIAL_TYPE_USER;
			$group->state = SOCIAL_STATE_PUBLISHED;
			$group->hits = 0;

			// Generate a unique key for this group which serves as a password
			$group->key = md5(FD::date()->toSql() . $this->my->password . uniqid());
		}

		// If there is still no alias generated, we need to automatically build one for the group
		if (!$group->alias) {
			$model = ES::model('Groups');
			$group->alias = $model->getUniqueAlias($group->getName());
		}

		$group->bind($data);
		$group->save();

		// After the group is created, assign the current user as the node item
		if ($isNew || $isCopy) {
			ES::access()->log('groups.limit', $this->my->id, $group->id, SOCIAL_TYPE_GROUP);

			$group->createOwner($this->my->id);
		}

		// Reconstruct args
		$args = array(&$data, &$group);

		$fieldsLib->trigger('onAdminEditAfterSave', $options['group'], $fields, $args);
		$group->bindCustomFields($data);

		// Reconstruct args
		$args = array(&$data, &$group);

		$fieldsLib->trigger('onAdminEditAfterSaveFields', $options['group'], $fields, $args);

		if ($isCopy) {
			$group->copyAvatar($cid);
			$group->copyCover($cid);
		}

		$message = 'COM_EASYSOCIAL_GROUPS_FORM_CREATE_SUCCESS';

		if ($isCopy) {
			$message = 'COM_EASYSOCIAL_GROUPS_FORM_COPIED_SUCCESS';
		}

		if ($id) {
			$message = 'COM_EASYSOCIAL_GROUPS_FORM_SAVE_UPDATE_SUCCESS';
		}

		$this->view->setMessage($message);
		return $this->view->call(__FUNCTION__, $task, $group);
	}

	/**
	 * Allows admin to toggle featured groups
	 *
	 * @since   1.3
	 * @access  public
	 */
	public function toggleDefault()
	{
		ES::checkToken();

		$task = $this->getTask();

		// Default message
		$message = 'COM_EASYSOCIAL_GROUPS_SET_FEATURED_SUCCESSFULLY';

		// Get the group object
		$ids = $this->input->get('cid', array(), 'array');

		foreach ($ids as $id) {

			// Load the group
			$group = ES::group($id);

			if ($task == 'toggleDefault') {

				if ($group->featured) {
					$group->removeFeatured();
					$message = 'COM_EASYSOCIAL_GROUPS_REMOVED_FEATURED_SUCCESSFULLY';
				}

				if (!$group->featured) {
					$group->setFeatured();
				}
			}

			if ($task == 'makeFeatured') {
				$group->setFeatured();
			}

			if ($task == 'removeFeatured') {
				$group->removeFeatured();

				$message = 'COM_EASYSOCIAL_GROUPS_REMOVED_FEATURED_SUCCESSFULLY';
			}
		}

		$this->view->setMessage($message);
		return $this->view->call('redirectToGroups');
	}

	/**
	 * Removes the group category avatar
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function removeCategoryAvatar()
	{
		ES::checkToken();

		$id = $this->input->get('id', 0, 'int');

		$category = ES::table('GroupCategory');
		$category->load($id);

		// Try to remove the avatar
		$category->removeAvatar();
	}

	/**
	 * Deletes a list of group from the site.
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function delete()
	{
		ES::checkToken();

		$ids = $this->input->get('cid', array(), 'int');

		if (!$ids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_DELETE_FAILED');
		}

		foreach ($ids as $id) {
			$group = ES::group((int) $id);
			$group->delete();
		}

		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_DELETED_SUCCESS');
		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Deletes a group category
	 *
	 * @since   1.0
	 * @access  public
	 */
	public function deleteCategory()
	{
		ES::checkToken();

		$ids = $this->input->get('cid', array(), 'int');

		if (!$ids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_CATEGORY_DELETED_FAILED');
		}

		foreach($ids as $id) {
			$category = ES::table('GroupCategory');
			$category->load((int) $id);

			$total = $category->getTotalGroups();

			// Check if deleting the category having the group will throw error.
			if ($total) {
				$this->view->setMessage('COM_EASYSOCIAL_CATEGORIES_DELETE_ERROR_GROUP_NOT_EMPTY', ES_ERROR);
				return $this->view->call(__FUNCTION__);
			}

			$state = $category->delete();
		}

		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_CATEGORY_DELETED_SUCCESS');
		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Toggles publishing state of groups
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function togglePublish()
	{
		ES::checkToken();

		$ids = $this->input->get('cid', array(), 'int');
		$task = $this->getTask();
		$indexer = ES::get('Indexer');
		$toggleValue = $task === 'publish' ? SOCIAL_CLUSTER_PUBLISHED : SOCIAL_CLUSTER_UNPUBLISHED;

		foreach ($ids as $id) {
			$group = ES::table('Group');
			$group->load((int) $id);

			$state = $group->$task();

			if ($state) {
				// need to update from the indexed item as well
				$indexer->itemStateChange('easysocial.groups', $id, $toggleValue);
			}
		}

		$message = 'COM_EASYSOCIAL_GROUPS_PUBLISHED_SUCCESS';

		if ($task == 'unpublish') {
			$message = 'COM_EASYSOCIAL_GROUPS_UNPUBLISHED_SUCCESS';
		}

		$this->view->setMessage($message);
		return $this->view->call('redirectToGroups');
	}

	/**
	 * Toggle publishing state of a group category
	 *
	 * @since   1.0
	 * @access  public
	 */
	public function togglePublishCategory()
	{
		ES::checkToken();

		$ids = $this->input->get('cid', array(), 'int');

		foreach ($ids as $id) {
			$category = ES::table('ClusterCategory');
			$category->load((int) $id);

			$task = $this->getTask() == 'publishCategory' ? 'publish' : 'unpublish';

			// Perform the action now
			$state = $category->$task();
		}

		$message = 'COM_EASYSOCIAL_GROUPS_CATEGORY_UNPUBLISHED_SUCCESS';

		if ($this->getTask() == 'publishCategory') {
			$message = 'COM_EASYSOCIAL_GROUPS_CATEGORY_PUBLISHED_SUCCESS';
		}

		$this->view->setMessage($message);

		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Allows caller to approve a group
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function approve()
	{
		ES::checkToken();

		$ids = $this->input->get('id', array(), 'int');
		$email = $this->input->get('email', '', 'default');

		if (!$ids) {
			return $this->view->exception('Sorry, but the group id provided is invalid.');
		}

		foreach ($ids as $id) {
			$group = ES::group((int) $id);
			$group->approve($email);
		}

		$this->view->setMessage('Group has been approved successfully.');

		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Allows caller to change a group owner
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function switchOwner()
	{
		ES::checkToken();

		$ids = $this->input->get('ids', array(), 'int');
		$userId = $this->input->get('userId', 0, 'int');
		$adminRights = $this->input->get('adminRights', '', 'default');

		if (!$ids || !$userId) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_INVALID_IDS');
		}

		foreach ($ids as $id) {
			$group = ES::group((int) $id);

			ES::access()->switchLogAuthor('groups.limit', $group->getCreator()->id, $group->id, SOCIAL_TYPE_GROUP, $userId);

			$group->switchOwner($userId, $adminRights);
		}

		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_GROUP_OWNER_UPDATED_SUCCESS');
		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Allows caller to reject a group
	 *
	 * @since   1.2
	 * @access  public
	 */
	public function reject()
	{
		ES::checkToken();

		$ids = $this->input->get('id', array(), 'array');
		$email = $this->input->get('email', '', 'default');
		$delete = $this->input->get('delete', '', 'default');
		$reason = $this->input->get('reason', '', 'default');

		if (!$ids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_INVALID_IDS');
		}

		foreach ($ids as $id) {
			$group = ES::group((int) $id);
			$group->reject($reason, $email, $delete);
		}

		$this->view->setMessage('Group has been rejected successfully.');
		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Method to update categories ordering
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function saveorder()
	{
		ES::checkToken();

		$cid = $this->input->get('cid', array(), 'array');

		if (!$cid) {
			return $this->view->exception('COM_EASYSOCIAL_PROFILES_ORDERING_NO_ITEMS');
		}

		$catId = $cid[0];
		$category = ES::table('clustercategory');
		$category->load($catId);
		$category->rebuildOrdering();

		$model = ES::model('ClusterCategory');

		$i = 1;

		foreach ($cid as $id) {
			$model->updateCategoriesOrdering($id, $i);
			$i++;
		}

		$this->view->setMessage('COM_EASYSOCIAL_PROFILES_ORDERING_UPDATED');
		return $this->view->call(__FUNCTION__);
	}

	/**
	 * Stores a group category ( Cluster category )
	 *
	 * @since   1.0
	 * @access  public
	 */
	public function saveCategory()
	{
		ES::checkToken();

		$post = $this->input->getArray('post');
		$id = $this->input->get('id', 0, 'int');
		$cid = $this->input->get('cid', 0, 'int');
		$task = $this->getTask();
		$isCopy = $task == 'saveCategoryCopy' ? true : false;
		$oriParentId = $this->input->get('oriParentId', 0);

		//unset oriParentId since we no longer needed
		unset($post['oriParentId']);

		// Category title is compulsory
		if (empty($post['title'])) {
			$this->view->setMessage('COM_ES_CLUSTER_CATEGORY_TITLE_MISSING', ES_ERROR);
			return $this->view->call(__FUNCTION__);
		}

		$category = ES::table('GroupCategory');

		if ($cid && $isCopy) {
			$category->load($cid);

			$post['id'] = $cid;
		} else {
			$category->load($id);
		}

		$category->bind($post);

		$workflowId = $this->input->get('workflow_id');

		// There workflow must be selected in order to proceed
		if (!$workflowId) {
			$this->view->setMessage('COM_ES_WORKFLOW_NOT_SELECTED', ES_ERROR);
			return $this->view->call(__FUNCTION__, $category);
		}

		$state = $category->store();

		// Bind the group creation access
		if ($state) {
			$categoryAccess = $this->input->get('create_access', '', 'default');
			$category->bindCategoryAccess('create', $categoryAccess);
		}

		// we need to check if the parent_id has changed or not. if yes,
		// we need to re-calcuate the lft and rgt boundary
		if ($oriParentId != $category->parent_id) {
			$category->updateLftValue($category->parent_id);
		}

		// lets re-arrange the lft right hierachy and ordering
		$category->rebuildOrdering();

		//now we need to update the ordering.
		$category->updateOrdering();

		// Store the avatar for this profile.
		$file = $this->input->files->get('avatar', '');

		// Try to upload the profile's avatar if required
		if (!empty($file['tmp_name'])) {
			$category->uploadAvatar($file);
		}

		// If this is a copy, copy over the avatar
		if ($isCopy) {
			$category->copyAvatar($id);
		}

		$category->assignWorkflow($workflowId);

		// Bind the access
		if (isset($post['access']) && !empty($post['access'])) {
			$category->bindAccess($post['access']);
		}

		// Set the message
		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_CATEGORY_SAVED_SUCCESS');
		return $this->view->call(__FUNCTION__, $category);
	}

	/**
	 * Create blank category.
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function createBlankCategory()
	{
		// Check for request forgeries.
		ES::checkToken();

		// Create the new category
		$newCategory = ES::table('GroupCategory');
		$newCategory->title = 'temp';
		$newCategory->createBlank(SOCIAL_TYPE_GROUP);
		$id = $newCategory->id;

		return $this->view->call(__FUNCTION__, $id);
	}

	/**
	 * Add members into this group
	 *
	 * @since  1.2
	 * @access public
	 */
	public function addMembers()
	{
		$groupId = $this->input->get('id', 0, 'int');
		$userIds = $this->input->get('members', '', 'string');
		$userIds = json_decode($userIds);

		$count = 0;
		$exists = array();

		ES::apps()->load(SOCIAL_TYPE_GROUP);
		$group = ES::group($groupId);

		foreach ($userIds as $id) {

			$id = (int) $id;

			$member = ES::table('GroupMember');
			$state = $member->load(array('uid' => $id, 'type' => SOCIAL_TYPE_USER, 'cluster_id' => $groupId));

			if ($state) {
				$exists[] = $id;
				continue;
			}

			// Admin adding members shouldn't worry about pending state. It should all go through regardless of the group openness.
			$member->cluster_id = $groupId;
			$member->uid = $id;
			$member->type = SOCIAL_TYPE_USER;
			$member->created = ES::date()->toSql();
			$member->state = SOCIAL_STATE_PUBLISHED;
			$member->owner = 0;
			$member->admin = 0;
			$member->invited_by = 0;
			$member->store();

			// Create stream when user accepts the invitation
			$group->createStream($id, 'join');
			
			$dispatcher = ES::dispatcher();

			// Trigger: onJoinGroup
			$dispatcher->trigger('user', 'onJoinGroup', array($id, $group));

			// @points: groups.join
			// Add points when user joins a group
			$points = ES::points();
			$points->assign('groups.join', 'com_easysocial', $id);

			// Notify members when a new member is added
			$group->notifyMembers('join', array('userId' => $id));

			// Transfer any existings events for invite only type
			$group->inviteToEvents($id, $this->my->id);

			$count++;
		}

		$msgType = SOCIAL_MSG_SUCCESS;
		$message = JText::sprintf('COM_EASYSOCIAL_GROUPS_ADD_MEMBERS_SUCCESS', $count);
		if ($exists) {
			if ($count) {
				$message = JText::sprintf('COM_ES_GROUPS_ADD_MEMBERS_SUCCESS_WITH_WARNING', $count);
			} else {
				$message = JText::_('COM_ES_GROUPS_ADD_MEMBERS_ALREADT_EXISTS');
				$msgType = SOCIAL_MSG_WARNING;
			}
		}

		$this->view->setMessage($message, $msgType);

		return $this->view->call('redirectToGroupForm', $groupId);
	}

	/**
	 * Remove members from this group
	 *
	 * @since  1.2
	 * @access public
	 */
	public function removeMembers()
	{
		ES::checkToken();

		$groupId = $this->input->get('id', 0, 'int');
		$ids = $this->input->get('cid', array(), 'int');
		$count = 0;

		foreach ($ids as $id) {
			$member = ES::table('GroupMember');
			$member->load($id);

			if ($member->isAdmin() || $member->isOwner()) {
				$this->view->setMessage('COM_EASYSOCIAL_GROUPS_REMOVE_MEMBERS_REMOVE_ADMIN_FAILED', ES_ERROR);
				return $this->view->call('redirectToGroupForm', $groupId);
			}

			$member->delete();
			$count++;
		}

		$this->view->setMessage(JText::sprintf('COM_EASYSOCIAL_GROUPS_REMOVE_MEMBERS_SUCCESS', $count));
		return $this->view->call('redirectToGroupForm', $groupId);
	}

	/**
	 * Publish a group member
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function publishUser()
	{
		ES::checkToken();

		$groupId = $this->input->get('id', 0, 'int');
		$cids = $this->input->get('cid', array(), 'int');

		if (!$cids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_PUBLISH_MEMBERS_FAILED');
		}

		foreach ($cids as $cid) {

			$node = ES::table('GroupMember');
			$node->load((int) $cid);

			if ($node->state == 1) {
				continue;
			}

			$node->state = 1;

			if (!$node->store()) {
				$this->view->setMessage('COM_EASYSOCIAL_GROUPS_PUBLISH_MEMBERS_FAILED', ES_ERROR);
				return $this->view->call('redirectToGroupForm', $groupId);
			}
		}

		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_PUBLISH_MEMBERS_MEMBERS_SUCCESS');
		return $this->view->call('redirectToGroupForm', $groupId);
	}

	/**
	 * Unpublish a group member
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function unpublishUser()
	{
		ES::checkToken();

		$groupId = $this->input->get('id', 0, 'int');
		$cids = $this->input->get('cid', array(), 'int');

		if (!$cids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_UNPUBLISH_MEMBERS_FAILED');
		}

		foreach ($cids as $cid) {

			$node = ES::table('GroupMember');
			$node->load((int) $cid);

			if ($node->state == 0 || $node->isAdmin() || $node->isOwner()) {
				continue;
			}

			$node->state = 0;

			if (!$node->store()) {
				$this->view->setMessage('COM_EASYSOCIAL_GROUPS_UNPUBLISH_MEMBERS_FAILED', ES_ERROR);
				return $this->view->call('redirectToGroupForm', $groupId);
			}
		}

		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_UNPUBLISH_MEMBERS_MEMBERS_SUCCESS');
		return $this->view->call('redirectToGroupForm', $groupId);
	}

	public function moveUp()
	{
		return $this->move(-1);
	}

	public function moveDown()
	{
		return $this->move(1);
	}

	private function move($index)
	{
		// Group and Group Categories both shares the same view and controller, so here we need to check for layout first to decide which ordering to move up and down

		// $layout could be categories (to add group in the future)
		$layout = $this->input->get('layout', '', 'string');
		$tablename = $layout === 'categories' ? 'groupcategory' : '';

		if (empty($tablename)) {
			return $this->view->move();
		}

		$ids = $this->input->get('cid', array(), 'int');

		if (!$ids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_CATEGORIES_INVALID_IDS');
		}

		$db = ES::db();
		$filter = $db->nameQuote('type') . ' = ' . $db->quote(SOCIAL_TYPE_GROUP);

		if (isset($ids[0])) {
			$table = ES::table($tablename);
			$table->load($ids[0]);

			$table->move($index, $filter);

			$table->updateOrdering();
		}

		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_CATEGORIES_ORDERED_SUCCESSFULLY');
		return $this->view->move($layout);
	}

	/**
	 * Promotes a group member to be a group admin
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function promoteMembers()
	{
		ES::checkToken();

		$groupId = $this->input->get('id', 0, 'int');
		$cids = $this->input->get('cid', array(), 'int');

		if (!$cids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_PROMOTE_MEMBERS_FAILED');
		}

		ES::language()->loadSite();

		$group = ES::group($groupId);

		$table = ES::table('GroupMember');
		$table->load(array('cluster_id' => $group->id, 'uid' => $this->my->id, 'type' => SOCIAL_TYPE_USER));

		if (!$this->my->isSiteAdmin() && !$table->isAdmin() && !$table->isOwner()) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_PROMOTE_MEMBERS_FAILED');
		}

		$count = 0;

		foreach ($cids as $id) {
			$member = ES::table('GroupMember');
			$member->load($id);

			$member->makeAdmin();
			$group->createStream($member->uid, 'makeadmin');

			// Notify the person that they are now a group admin
			$emailOptions = array(
				'title' => 'COM_EASYSOCIAL_GROUPS_EMAILS_PROMOTED_AS_GROUP_ADMIN_SUBJECT',
				'template' => 'site/group/promoted',
				'permalink' => $group->getPermalink(true, true),
				'actor' => $this->my->getName(),
				'actorAvatar' => $this->my->getAvatar(SOCIAL_AVATAR_SQUARE),
				'actorLink' => $this->my->getPermalink(true, true),
				'group' => $group->getName(),
				'groupLink' => $group->getPermalink(true, true)
			);

			$systemOptions = array(
				'context_type' => 'groups.group.promoted',
				'url' => $group->getPermalink(false, false, 'item', false),
				'actor_id' => $this->my->id,
				'uid' => $group->id
			);

			// Notify the owner first
			ES::notify('groups.promoted', array($member->uid), $emailOptions, $systemOptions);

			$count++;
		}

		$this->view->setMessage(JText::sprintf('COM_EASYSOCIAL_GROUPS_PROMOTE_MEMBERS_SUCCESS', $count));
		return $this->view->call('redirectToGroupForm', $group->id);
	}

	/**
	 * Demotes a group admin to normal member
	 *
	 * @since	2.1.0
	 * @access	public
	 */
	public function demoteMembers()
	{
		ES::checkToken();

		$groupId = $this->input->get('id', 0, 'int');
		$cids = $this->input->get('cid', array(), 'int');

		if (!$cids) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_DEMOTE_MEMBERS_FAILED');
		}

		$group = ES::group($groupId);

		$table = ES::table('GroupMember');
		$table->load(array('cluster_id' => $group->id, 'uid' => $this->my->id, 'type' => SOCIAL_TYPE_USER));

		if (!$this->my->isSiteAdmin() && !$table->isOwner()) {
			return $this->view->exception('COM_EASYSOCIAL_GROUPS_DEMOTE_MEMBERS_FAILED');
		}

		$count = 0;

		foreach ($cids as $id) {
			$member = ES::table('GroupMember');
			$member->load($id);
			$member->revokeAdmin();

			$count++;
		}

		$this->view->setMessage(JText::sprintf('COM_EASYSOCIAL_GROUPS_DEMOTE_MEMBERS_SUCCESS', $count));
		return $this->view->call('redirectToGroupForm', $groupId);
	}

	/**
	 * Allows admin to switch a group's category
	 *
	 * @since   2.1.0
	 * @access  public
	 */
	public function switchCategory()
	{
		ES::checkToken();

		$ids = ES::makeArray($this->input->get('cid'));
		$categoryId = $this->input->getInt('category');

		$model = ES::model('GroupCategories');

		foreach ($ids as $id) {
			$model->updateGroupCategory($id, $categoryId);
		}

		$this->view->setMessage('COM_EASYSOCIAL_GROUPS_SWITCH_CATEGORY_SUCCESSFUL');
		return $this->view->call('redirectToGroups');
	}
}
