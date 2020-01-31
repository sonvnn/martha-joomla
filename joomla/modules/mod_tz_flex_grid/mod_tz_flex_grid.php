<?php
/*------------------------------------------------------------------------

# TZ Flex Grid Module

# ------------------------------------------------------------------------

# Author:    Sonny

# Copyright: Copyright (C) 2011-2019 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

JLoader::import('com_tz_portfolio_plus.libraries.helper.modulehelper', JPATH_ADMINISTRATOR.'/components');

JHtml::_('jquery.framework');

$list            = ModTZ_Flex_GridHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

if($list) {
    $doc = JFactory::getDocument();
	$doc -> addStyleSheet('components/com_tz_portfolio_plus/css/all.min.css');
    $doc->addStyleSheet('modules/mod_tz_flex_grid/css/style.min.css');
	$doc->addScript('modules/mod_tz_flex_grid/js/script.min.js');
	$gallerytype                            =   $params->get('flex_grid_gallery_type','vertical_masonry');
	$flex_grid_style                        =   $params->get('flex_grid_style','1');
	$flex_grid_width                        =   $params->get('flex_grid_width','300');
	$flex_grid_height                       =   $params->get('flex_grid_height','300');
	$flex_vertical_masonry_gallery_height   =   $params->get('flex_vertical_masonry_gallery_height','1000');
	$flex_vertical_masonry_min_columns      =   $params->get('flex_vertical_masonry_min_columns','3');
	$flex_horizontal_masonry_grid_height    =   $params->get('flex_horizontal_masonry_grid_height','250');
	$flex_grid_gallery_hover_effect         =   $params->get('flex_grid_gallery_hover_effect','default');
	if($params -> get('enable_bootstrap', 0)) {
		if( $params -> get('bootstrapversion', 3) == 4) {
			$doc->addScript(TZ_Portfolio_PlusUri::base(true) . '/vendor/bootstrap/js/bootstrap.min.js',
				array('version' => 'auto'));
			$doc->addStyleSheet(TZ_Portfolio_PlusUri::base(true) . '/vendor/bootstrap/css/bootstrap.min.css',
				array('version' => 'auto'));
			$doc->addScript(TZ_Portfolio_PlusUri::base(true) . '/vendor/bootstrap/js/bootstrap.bundle.min.js',
				array('version' => 'auto'));
		}else{
			$doc->addStyleSheet(TZ_Portfolio_PlusUri::base(true)
				. '/bootstrap/css/bootstrap.min.css', array('version' => 'auto'));
			$doc -> addScript(TZ_Portfolio_PlusUri::base(true).'/bootstrap/js/bootstrap.min.js',
				array('version' => 'auto'));
		}
	}
	$show_filter = $params->get('show_filter',1);
	$categories = ModTZ_Flex_GridHelper::getCategoriesByArticle($params);
	$tags = ModTZ_Flex_GridHelper::getTagsByArticle($params);
	if($show_filter) {
		$filter_tag = ModTZ_Flex_GridHelper::getTagsFilterByArticle($params);
		$filter_cat = ModTZ_Flex_GridHelper::getCategoriesFilterByArticle($params);
		$doc->addScriptDeclaration('
			jQuery(function($){
			    $(document).ready(function(){
			        tz_flex_grid.filter(\''.$module -> id.'\', $);
			    });
			});
		');
	}
	if ($params -> get('show_lightbox', 1)){
		$doc -> addStyleSheet('components/com_tz_portfolio_plus/css/jquery.fancybox.min.css');
		$doc -> addScript('components/com_tz_portfolio_plus/js/jquery.fancybox.min.js');
		$lightboxopt                            =   $params->get('flex_grid_lightbox_option',array('zoom', 'slideShow', 'fullScreen', 'thumbs', 'close'));
		if ($lightboxopt && is_array($lightboxopt)) {
			for ($i = 0 ; $i< count($lightboxopt); $i++) {
				$lightboxopt[$i]  =   '"'.$lightboxopt[$i].'"';
			}
			$lightboxopt=   is_array($lightboxopt) ? implode(',', $lightboxopt) : '';
		} else {
			$lightboxopt=   '';
		}
		$doc->addScriptDeclaration('
			jQuery(function($){
			    $(document).ready(function(){
			        tz_flex_grid.lightbox( ['.$lightboxopt.'], \'tz-flex-grid-'.$module -> id.'\', $);
			    });
			});
		');
	}

	switch ($gallerytype) {
		case 'vertical_masonry':
			$style  =   '#tz-flex-grid-'.$module -> id.' > .vertical_masonry {max-height:100%;}';
			$style  .=   '@media (min-width: 992px) {#tz-flex-grid-'.$module -> id.' > .vertical_masonry {max-height:'.$flex_vertical_masonry_gallery_height.'px;}}';
			for ($i = 0; $i<count($list); $i++) {
				$style      .=   '#tz-flex-grid-'.$module -> id.' > .vertical_masonry > div:nth-child('.($i+1).') {height: '.(rand(300,600)).'px;}';
			}
			$doc -> addStyleDeclaration($style);
			$doc->addScriptDeclaration('
			jQuery(function($){
			    $(document).ready(function(){
			        tz_flex_grid.verticle_masonry('.$flex_vertical_masonry_min_columns.',\'tz-flex-grid-'.$module -> id.'\', $);
			    });
			});
			');
			break;

		case 'horizontal_masonry':
			$style  =   '#tz-flex-grid-'.$module -> id.' > .horizontal_masonry .tz-flex-item {height:'.$flex_horizontal_masonry_grid_height.'px;}';
			$style  .=   '@media (max-width: 767px) {#tz-flex-grid-'.$module -> id.' > .horizontal_masonry > div {max-width:100%; width: 320px !important;}}';
			for ($i = 0; $i<count($list); $i++) {
				$style      .=   '#tz-flex-grid-'.$module -> id.' > .horizontal_masonry > div:nth-child('.($i+1).') {width: '.(rand(250,600)).'px;}';
			}
			$doc -> addStyleDeclaration($style);
			break;

		case 'grid':
			$style  =   '#tz-flex-grid-'.$module -> id.' > .grid {grid-template-columns: repeat(auto-fill, minmax('.$flex_grid_width.'px, 1fr)); grid-auto-rows: minmax('.$flex_grid_height.'px, auto);}';
			$doc -> addStyleDeclaration($style);
			if ($flex_grid_style > 1) {
				$doc->addScriptDeclaration('
				jQuery(function($){
					$(document).ready(function(){
					    tz_flex_grid.grid_style_'.$flex_grid_style.'(\'tz-flex-grid-'.$module -> id.'\', '.$flex_grid_width.', $);
					    $(window).on("resize", function () { tz_flex_grid.grid_style_'.$flex_grid_style.'(\'tz-flex-grid-'.$module -> id.'\', '.$flex_grid_width.', $);});
					});
				});
				');
			}
			break;
	};
}

require JModuleHelper::getLayoutPath('mod_tz_flex_grid', $params->get('layout', 'default'));