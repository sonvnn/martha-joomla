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

class plgFinderEasySocialVideos extends FinderIndexerAdapter
{
	protected $context = 'EasySocial.Videos';
	protected $extension = 'com_easysocial';
	protected $layout = 'item';
	protected $type_title = 'EasySocial.Videos';
	protected $table = '#__social_videos';

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
		if ($context == 'easysocial.videos')
		{
			$id = $table->id;

			$db = FD::db();
			$sql = $db->sql();

			$query = "select `link_id` from `#__finder_links` where `url` like '%option=com_easysocial&view=videos&id=$id%'";
			$sql->raw($query);
			$db->setQuery($sql);
			$item = $db->loadResult();

			if ($item) {
				// Index the item.
				if( FD::isJoomla30() )
				{
					$this->indexer->remove($item);
				}
				else
				{
					FinderIndexer::remove( $item );
				}
			}

			return true;

		}
		elseif ($context == 'com_finder.index')
		{
			$id = $table->link_id;
		}
		else
		{
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
		if ($context == 'easysocial.videos'
			&& $row
			&& $row->state == '1') {
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
		// Check if the extension is enabled
		if (JComponentHelper::isEnabled($this->extension) == false) {
			return;
		}

		$access = 1;

		if (is_null($item->privacy)) {
			$privacy = FD::privacy($item->user_id);
			$privacyValue = $privacy->getValue('videos', 'view');
			$item->privacy = $privacyValue;
		}

		if ($item->privacy == SOCIAL_PRIVACY_PUBLIC) {
			$access = 1;
		} else if ($item->privacy == SOCIAL_PRIVACY_MEMBER) {
			$access = 2;
		} else {
			// this is not public / member items. do not index this item

			// also we need to delete this item if there is indexed previously.
			$videoTbl = ES::table('Video');
			$videoTbl->load($item->id);

			$this->onFinderAfterDelete('easysocial.videos', $videoTbl);
			return;
		}

		// $sql->select('a.id, a.title, a.alias, a.introtext AS summary, a.fulltext AS body');
		// $sql->select('a.state, a.catid, a.created AS start_date, a.created_by');
		// $sql->select('a.created_by_alias, a.modified, a.modified_by, a.attribs AS params');
		// $sql->select('a.metakey, a.metadesc, a.metadata, a.language, a.access, a.version, a.ordering');
		// $sql->select('a.publish_up AS publish_start_date, a.publish_down AS publish_end_date');
		// $sql->select('c.title AS category, c.published AS cat_state, c.access AS cat_access');


		// album onwer
		$user = ES::user($item->user_id);
		$userAlias = $user->getAlias(false);

		$videoTbl = ES::table('Video');
		$videoTbl->load($item->id);
		$video = ES::video($videoTbl->uid, $videoTbl->type, $videoTbl->id);

		// Build the necessary route and path information.
		// index.php?option=com_easysocial&view=videos&id=7&layout=item&Itemid=799

		$item->url = 'index.php?option=com_easysocial&view=videos&id=' . $video->id . '&layout=item';

		$item->route = $video->getPermalink(true, null, null, false, false, false);
		$item->route = $this->removeAdminSegment($item->route);
		$item->path = FinderIndexerHelper::getContentPath($item->route);

		$item->access = $access;
		$item->alias = JFilterOutput::stringURLSafe($item->title);
		$item->state = 1;
		$item->catid = $video->category_id;
		$item->start_date = $video->created;
		$item->created_by = $video->user_id;
		$item->created_by_alias	= $userAlias;
		$item->modified	 = $video->created;
		$item->modified_by = $video->user_id;
		$item->params = '';
		$item->metakey = $item->category . ' ' . $video->title;
		$item->metadesc = $video->title . ' ' . $video->description;
		$item->metadata = '';
		$item->publish_start_date = $video->created;
		$item->category = $item->category;
		$item->cat_state = 1;
		$item->cat_access = 0;

		$item->summary = $video->title . ' ' . $video->description;
		$item->body = $video->title . ' ' . $video->description;

		// Add the meta-author.
		$item->metaauthor = $userAlias;
		$item->author = $userAlias;

		// add image param
		$registry = ES::registry();
		$registry->set('image', $video->getThumbnail());

		$item->params = $registry;

		// Add the meta-data processing instructions.
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metakey');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metadesc');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metaauthor');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'author');

		// Add the type taxonomy data.
		$item->addTaxonomy('Type', 'EasySocial.Videos');

		// Add the author taxonomy data.

		$item->addTaxonomy('Author', $userAlias);

		// Add the category taxonomy data.
		$item->addTaxonomy('Category', $item->category, $item->cat_state, $item->cat_access);

		// Add the language taxonomy data.
		// $langParams 	= JComponentHelper::getParams('com_languages');
		// $item->language = $langParams->get( 'site', 'en-GB');

		$item->language = '*';

		$item->addTaxonomy('Language', $item->language);

		// Get content extras.
		FinderIndexerHelper::getContentExtras($item);

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
			$url = ltrim( $url , '/' );
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
		require_once( JPATH_ROOT .  '/administrator/components/com_easysocial/includes/foundry.php' );
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

		$sql->select( 'a.*, b.title AS category');
        $sql->select('a.id AS ordering');
        $sql->select('c.value AS privacy');
 		$sql->from('#__social_videos AS a');
		$sql->join( 'INNER', '#__social_videos_categories AS b on a.category_id = b.id');
		$sql->join('LEFT', '#__social_privacy_items AS c ON a.id = c.uid and c.type = ' . $db->Quote('videos'));
		$sql->where( 'a.state = 1');

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
		if ($context === 'easysocial.videos') {

			$db = ES::db();

			$indexedURL = "'%option=com_easysocial&view=videos&id=$id%'";

			$query = 'UPDATE `#__finder_links` SET ' . $db->nameQuote('state') . ' = ' . $db->Quote($value);
			$query .= ' , ' . $db->nameQuote('published') . ' = ' . $db->Quote($value);
			$query .= ' WHERE ' . $db->nameQuote('url') . ' LIKE ' . $indexedURL;

			$db->setQuery($query);
			$db->query();
		}
	}
}
