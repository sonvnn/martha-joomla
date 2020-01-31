<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Carousel Module

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2018 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

JLoader::register('TZ_Portfolio_PlusHelperRoute', JPATH_SITE . '/components/com_tz_portfolio_plus/helpers/route.php');
JLoader::import('com_tz_portfolio_plus.helpers.categories', JPATH_SITE.'/components');

//JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_tz_portfolio_plus/models', 'TZ_Portfolio_PlusModel');

abstract class ModTZ_Portfolio_Plus_CarouselHelper{

    protected static $cache = array();
    protected static $moduleName = 'mod_tz_portfolio_plus_carousel';

    public static function getList(&$params, $module = null){

        if($module){
            if(is_object($module)){
                self::$moduleName = $module -> module;
            }elseif(is_string($module)){
                self::$moduleName   = $module;
            }
        }

        $storeId    = __METHOD__.'::'.md5($params -> toString());

        if(!isset(self::$cache[$storeId])){
            self::$cache[$storeId]  = false;
        }

        if(!self::$cache[$storeId]){
            $db     = JFactory::getDbo();
            $query  = self::getListQuery($params);
            $db -> setQuery($query, 0, $params -> get('article_limit', 5));
            if($items = $db -> loadObjectList()){

                $app    = JFactory::getApplication();

                $ssl    = 2;
                $uri    = JUri::getInstance();
                if($uri -> isSsl()){
                    $ssl    = 1;
                }

                JPluginHelper::importPlugin('content');
                TZ_Portfolio_PlusPluginHelper::importPlugin('content');
                TZ_Portfolio_PlusPluginHelper::importPlugin('mediatype');

                $app -> triggerEvent('onAlwaysLoadDocument', array('modules.'.self::$moduleName));
                $app -> triggerEvent('onLoadData', array('modules.'.self::$moduleName, $items, $params));

                $content_ids        = ArrayHelper::getColumn($items, 'id');
                $mainCategories     = TZ_Portfolio_PlusFrontHelperCategories::getCategoriesByArticleId($content_ids,
                    array('main' => true));
                $second_categories  = TZ_Portfolio_PlusFrontHelperCategories::getCategoriesByArticleId($content_ids,
                    array('main' => false));

                foreach($items as $i => &$item){
                    $item -> params = clone($params);

                    $app -> triggerEvent('onTPContentBeforePrepare', array('modules.'.self::$moduleName,
                        &$item, &$item -> params));

                    // Create Article Link
                    $item -> link       = JRoute::_(TZ_Portfolio_PlusHelperRoute::getArticleRoute($item -> slug, $item -> catid, $item -> language));
                    $item -> fullLink   = JRoute::_(TZ_Portfolio_PlusHelperRoute::getArticleRoute($item -> slug, $item -> catid, $item -> language), true, $ssl);
                    $item -> authorLink = JRoute::_(TZ_Portfolio_PlusHelperRoute::getUserRoute($item -> created_by, $params->get('usermenuitem', 'auto')));
                    $item -> layout     = $params->get('layout');

                    // Set main category
                    if($mainCategories && isset($mainCategories[$item -> id])) {
                        $mainCategory = $mainCategories[$item->id];
                        if ($mainCategory) {
                            $item -> catid = $mainCategory->id;
                            $item -> category_title = $mainCategory->title;
                            $item -> catslug = $mainCategory->id . ':' . $mainCategory->alias;
                            $item -> category_link = $mainCategory->link;
                        }
                    }

                    // Get all second categories
                    $item -> second_categories  = null;
                    if(isset($second_categories[$item -> id])) {
                        $item -> second_categories = $second_categories[$item -> id];
                    }

                    $media      = $item -> media;
                    if(!empty($media)) {
                        $registry = new Registry($media);

                        $media = $registry->toObject();
                        $item->media = $media;
                    }

                    $item -> mediatypes = array();

                    $item -> event  = new stdClass();

                    //Call trigger in group content
                    if (!isset($item->text))
                    {
                        $item->text = $item->introtext;
                    }

                    $results = $app -> triggerEvent('onContentPrepare', array ('modules.'.self::$moduleName, &$item, &$params, 0));
                    $item->introtext = $item->text;

                    if($introtext_limit = $params -> get('introtext_limit')){
		                $item -> introtext  = '<p>'.JHtml::_('string.truncate', $item->introtext, $introtext_limit, true, false).'</p>';
	                }

                    $results = $app -> triggerEvent('onContentBeforeDisplay', array('modules.'.self::$moduleName,
                        &$item, &$params, 0, $params->get('layout', 'default')));
                    $item->event->beforeDisplayContent = trim(implode("\n", $results));

                    $results = $app -> triggerEvent('onContentAfterDisplay', array('modules.'.self::$moduleName,
                        &$item, &$params, 0, $params->get('layout', 'default')));
                    $item->event->afterDisplayContent = trim(implode("\n", $results));

                    // Process the tz portfolio's content plugins.
                    $results    = $app -> triggerEvent('onBeforeDisplayAdditionInfo',array('modules.'.self::$moduleName,
                        &$item, &$params, 0, $params->get('layout', 'default')));
                    $item -> event -> beforeDisplayAdditionInfo   = trim(implode("\n", $results));

                    $results    = $app -> triggerEvent('onAfterDisplayAdditionInfo',array('modules.'.self::$moduleName,
                        &$item, &$params, 0, $params->get('layout', 'default')));
                    $item -> event -> afterDisplayAdditionInfo   = trim(implode("\n", $results));

                    $results    = $app -> triggerEvent('onContentDisplayListView',array('modules.'.self::$moduleName,
                        &$item, &$params, 0, $params->get('layout', 'default')));
                    $item -> event -> contentDisplayListView   = trim(implode("\n", $results));

                    //Call trigger in group tz_portfolio_plus_mediatype
                    $results    = $app -> triggerEvent('onContentDisplayMediaType',array('modules.'.self::$moduleName,
                        &$item, &$params, 0, $params->get('layout', 'default')));
                    if(isset($item) && $item){
                        $item -> event -> onContentDisplayMediaType    = trim(implode("\n", $results));
                        if($results    = $app -> triggerEvent('onAddMediaType')){
                            $mediatypes = array();
                            foreach($results as $result){
                                if(isset($result -> special) && $result -> special) {
                                    $mediatypes[] = $result -> value;
                                }
                            }
                            $item -> mediatypes = $mediatypes;
                        }
                    }
                    else{
                        // Unset item if add-on is special (Examples: Add-On Link and Quote).
                        unset($items[$i]);
                    }

                    $app -> triggerEvent('onTPContentAfterPrepare', array('modules.'.self::$moduleName,
                        &$item, &$item -> params, 0, $params->get('layout', 'default')));
                }
                self::$cache[$storeId]  = $items;
            }
        }

        return self::$cache[$storeId];
    }

    protected static function getListQuery($params){
        $db     = JFactory::getDbo();
        $user   = JFactory::getUser();
        $query  = $db -> getQuery(true);

        $query -> select('c.*, c.id as content_id,t.title AS tagName, m.catid AS catid ,cc.title AS category_title');
        $query -> select('u.name AS author');
        $query -> select('CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as slug');
        $query -> select('CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug');
        $query -> select('CASE WHEN CHAR_LENGTH(c.fulltext) THEN c.fulltext ELSE null END as readmore');

        $query -> from($db -> quoteName('#__tz_portfolio_plus_content').' AS c');

        $query -> join('INNER',$db -> quoteName('#__tz_portfolio_plus_content_category_map').' AS m ON m.contentid=c.id');
        $query -> join('LEFT',$db -> quoteName('#__tz_portfolio_plus_categories').' AS cc ON cc.id=m.catid');
        $query -> join('LEFT',$db -> quoteName('#__tz_portfolio_plus_tag_content_map').' AS x ON x.contentid=c.id');
        $query -> join('LEFT',$db -> quoteName('#__tz_portfolio_plus_tags').' AS t ON t.id=x.tagsid');
        $query -> join('LEFT',$db -> quoteName('#__users').' AS u ON u.id=c.created_by');


        // Join over the categories to get parent category titles
        $query -> select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias');
        $query -> join('LEFT', '#__tz_portfolio_plus_categories as parent ON parent.id = cc.parent_id');

        $query -> where('c.state = 1');

        if($params -> get('show_noauth', 0)){
            $groups	= implode(',', $user -> getAuthorisedViewLevels());

            $query -> where('c.access IN ('.$groups.')');
            $query -> where('cc.access IN ('.$groups.')');
        }

        // Filter by categories
        $catids = $params -> get('catid', array());
        if(is_array($catids)){
            $catids = array_filter($catids);
            if(count($catids)){
                $query -> where('m.catid IN('.implode(',',$catids).')');
            }
        }
        elseif(!empty($catids)){
            $query -> where('m.catid IN('.$catids.')');
        }

        // Filter by media types
        if($types = $params -> get('media_types',array())){
            $types  = array_filter($types);
            if(count($types)) {
                $media_conditions   = array();
                foreach($types as $type){
                    $media_conditions[] = 'c.type='.$db -> quote($type);
                }
                if(count($media_conditions)){
                    $query -> andWhere($media_conditions);
                }
            }
        }

        switch ($params -> get('orderby_sec', 'rdate')){
            default:
                $orderby    = 'c.id DESC';
                break;
            case 'rdate':
                $orderby    = 'c.created DESC';
                break;
            case 'date':
                $orderby    = 'c.created ASC';
                break;
            case 'alpha':
                $orderby    = 'c.title ASC';
                break;
            case 'ralpha':
                $orderby    = 'c.title DESC';
                break;
            case 'author':
                $orderby    = 'u.name ASC';
                break;
            case 'rauthor':
                $orderby    = 'u.name DESC';
                break;
            case 'hits':
                $orderby    = 'c.hits DESC';
                break;
            case 'rhits':
                $orderby    = 'c.hits ASC';
                break;
            case 'order':
                $orderby    = 'c.ordering ASC';
                break;
        }

        $query -> order($orderby);

        $query -> group('c.id');

        return $query;
    }
}