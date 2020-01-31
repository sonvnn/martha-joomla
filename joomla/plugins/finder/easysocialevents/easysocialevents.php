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

jimport('joomla.application.component.helper');
jimport('joomla.filesystem.file');

// Load the base adapter.
$finderLibFile = JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';
if (!JFile::exists($finderLibFile)) {
	return;
}
require_once $finderLibFile;

class plgFinderEasySocialEvents extends FinderIndexerAdapter
{
	protected $context = 'EasySocial.Events';
	protected $extension = 'com_easysocial';
	protected $layout = 'item';
	protected $type_title = 'EasySocial.Events';
	protected $table = '#__social_cluster';

	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Method to remove the link information for items that have been deleted.
	 *
	 * @since	2.1
	 * @access	public
	 */
	public function onFinderAfterDelete($context, $table)
	{
		if ($context == 'easysocial.events') {
			$id = $table->id;

			$db = ES::db();
			$sql = $db->sql();

			$query = "select `link_id` from `#__finder_links` where `url` like '%option=com_easysocial&view=events&id=$id%'";
			$sql->raw($query);
			$db->setQuery($sql);
			$item = $db->loadResult();

			if ($item) {

				// Index the item.
				if (ES::isJoomla30()) {
					$this->indexer->remove($item);
				} else {
					FinderIndexer::remove($item);
				}
			}

			return true;


		} elseif ($context == 'com_finder.index') {
			$id = $table->link_id;
		} else {
			return true;
		}

		// Remove the items.
		return $this->remove($id);
	}

	/**
	 * Method to determine if the access level of an item changed.
	 *
	 * @since	2.1
	 * @access	public
	 */
	public function onFinderAfterSave($context, $row, $isNew)
	{
		// We only want to handle articles here
		if ($context == 'easysocial.events'
			&& $row
			&& $row->state == '1'
			&& $row->cluster_type == 'event'
			&& ($row->type == '1' || $row->type == '2' || $row->type == '4')
			) {

			// Reindex the item
			$this->reindex($row->id);
		}

		return true;
	}

	/**
	 * Method to index an item. The item must be a FinderIndexerResult object.
	 *
	 * @since	2.1
	 * @access	protected
	 */
	protected function index(FinderIndexerResult $item, $format = 'html')
	{
		// Prevent possibility of indexer running more than once.
		static $indexedItems = array();

		// Check if the extension is enabled
		if (JComponentHelper::isEnabled($this->extension) == false) {
			return;
		}

		// If this was already indexed on the same page request, do not try to index it again.
		if (isset($indexedItems[$item->id])) {
			return;
		}

		$access = 1;

		if ($item->type == SOCIAL_EVENT_TYPE_PRIVATE) {
			$access = 2;
		}

		// album onwer
		$user = ES::user($item->creator_uid);
		$userAlias = $user->getAlias(false);

		$event = ES::event($item->id);
		$item->url = 'index.php?option=com_easysocial&view=events&id=' . $event->id . '&layout=item';

		$item->route = $event->getPermalink(true, false, 'item', false);
		$item->route = $this->removeAdminSegment($item->route);
		$item->path = FinderIndexerHelper::getContentPath($item->route);

		$item->access = $access;
		$item->alias = $event->getAlias();
		$item->state = 1;
		$item->catid = $event->category_id;
		$item->start_date = $event->created;
		$item->created_by = $event->creator_uid;
		$item->created_by_alias = $userAlias;
		$item->modified = $event->created;
		$item->modified_by = $event->creator_uid;
		$item->params = '';
		$item->metakey = $item->category . ' ' . $event->title;
		$item->metadesc = $event->title . ' ' . $event->description;
		$item->metadata = '';
		$item->publish_start_date = $event->created;
		$item->category = $item->category;
		$item->cat_state = 1;
		$item->cat_access = 0;

		$item->summary = $event->title . ' ' . $event->description;
		$item->body = $event->title . ' ' . $event->description;

		// Add the meta-author.
		$item->metaauthor = $userAlias;
		$item->author = $userAlias;

		// add image param
		$registry = ES::registry();
		$registry->set('image', $event->getAvatar());

		$item->params = $registry;

		// Add the meta-data processing instructions.
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metakey');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metadesc');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metaauthor');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'author');

		// Add the type taxonomy data.
		$item->addTaxonomy('Type', 'EasySocial.Events');

		// Add the author taxonomy data.
		$item->addTaxonomy('Author', $userAlias);

		// Add the category taxonomy data.
		$item->addTaxonomy('Category', $item->category, $item->cat_state, $item->cat_access);

		$item->language = '*';

		$item->addTaxonomy('Language', $item->language);

		// Get content extras.
		FinderIndexerHelper::getContentExtras($item);

		// Cache the indexed item now to prevent multiple storage.
		$indexedItems[$item->id] = true;

		// Index the item.
		if (ES::isJoomla30()) {
			$this->indexer->index($item);
		} else {
			FinderIndexer::index($item);
		}
	}

	/**
	 * Remove admin segments from the url
	 *
	 * @since	2.1
	 * @access	private
	 */
	private function removeAdminSegment($url = '')
	{
		if ($url) {
			$url = ltrim($url, '/');
			$url = str_replace('administrator/', '', $url);
		}

		return $url;
	}

	/**
	 * Method to setup the indexer to be run.
	 *
	 * @since	2.1
	 * @access	public
	 */
	protected function setup()
	{
		// Load dependent classes.
		require_once(JPATH_ROOT .  '/administrator/components/com_easysocial/includes/foundry.php');
		return true;
	}

	/**
	 * Method to get the SQL query used to retrieve the list of content items.
	 *
	 * @since	2.1
	 * @access	public
	 */
	protected function getListQuery($sql = null)
	{
		$db = JFactory::getDbo();

		// Check if we can use the supplied SQL query.
		$sql = is_a($sql, 'JDatabaseQuery') ? $sql : $db->getQuery(true);

		$sql->select('a.*, b.title AS category');
		$sql->select('a.id AS ordering');
		$sql->from('#__social_clusters AS a');
		$sql->join('INNER', '#__social_clusters_categories AS b on a.category_id = b.id');
		$sql->where('a.state = 1');
		$sql->where('a.cluster_type = ' . $db->Quote('event'));
		$sql->where('a.type IN (1,2,4)');

		return $sql;
	}

	/**
	 * Method to change the item state from the #__finder_links table during publish/unpublish action
	 *
	 * @since	2.1
	 * @access	public
	 */
	public function onFinderChangeState($context, $id, $value)
	{
		if ($context === 'easysocial.events') {

			$db = ES::db();

			$indexedURL = "'%option=com_easysocial&view=events&id=$id%'";

			$query = 'UPDATE `#__finder_links` SET ' . $db->nameQuote('state') . ' = ' . $db->Quote($value);
			$query .= ' , ' . $db->nameQuote('published') . ' = ' . $db->Quote($value);
			$query .= ' WHERE ' . $db->nameQuote('url') . ' LIKE ' . $indexedURL;

			$db->setQuery($query);
			$db->query();
		}
	}
}
