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

// Include the latest functions only once
JLoader::register('ModTZ_Portfolio_Plus_CarouselHelper', __DIR__ . '/helper.php');

JLoader::import('com_tz_portfolio_plus.libraries.helper.modulehelper', JPATH_ADMINISTRATOR.'/components');
JLoader::import('extrafields', COM_TZ_PORTFOLIO_PLUS_SITE_HELPERS_PATH);

JHtml::_('jquery.framework');

$list            = ModTZ_Portfolio_Plus_CarouselHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

if($list) {

    $doc = JFactory::getDocument();
    $doc->addStyleSheet(JUri::base(true) . '/modules/' . $module->module . '/css/owl.carousel.min.css');
    $doc->addStyleSheet(JUri::base(true) . '/modules/' . $module->module . '/css/owl.theme.default.min.css');
    $doc->addStyleSheet(JUri::base(true) . '/modules/' . $module->module . '/css/style.css');
    $doc->addScript(JUri::base(true) . '/modules/' . $module->module . '/js/owl.carousel.min.js');

    // Process responsive of carousel script options
    $responsive     = array();
    $crResponsive   = $params -> get('cr_responsive', '{"width":992,"item":3},{"width":768,"item":2},{"width":0,"item":1}');
    if(!is_array($crResponsive) && preg_match_all('/(\{.*?\})/',$crResponsive,$match)){
        $crResponsive   = $match[1];
    }
    if(count($crResponsive)){
        foreach($crResponsive as $cr){
            $crRes  = json_decode($cr);
            $responsive[]   = $crRes -> width.':{items:'.$crRes -> item.'}';
        }
    }

    $doc->addScriptDeclaration('
        (function($){
            "use strict";
            $(document).ready(function(){
                var owl_'.$module->id.' = $("#module__' . $module->id . ' .owl-carousel");
                owl_'.$module->id.'.owlCarousel({
                    loop: '.($params -> get('cr_loop', 0)?'true':'false').',
                    nav: '.($params -> get('cr_nav', 1)?'true':'false').',
                    dots: '.($params -> get('cr_dots', 1)?'true':'false').',
                    dotsEach: true,
                    slideBy: '.$params -> get('cr_slideBy', 1).',
                    lazyLoad: '.($params -> get('cr_lazyLoad', 1)?'true':'false').',
                    autoplay: '.($params -> get('cr_autoplay', 0)?'true':'false').',
                    autoplayTimeout: '.($params -> get('cr_autoplayTimeout', 5000)?'true':'false').',
                    center: '.($params -> get('cr_center', 0)?'true':'false').',
                    autoWidth: '.($params -> get('cr_autoWidth', 0)?'true':'false').',
                    autoHeight: '.($params -> get('cr_autoHeight', 0)?'true':'false').',
                    rtl: '.($params -> get('cr_rtl', 0)?'true':'false').',
                    items: '.$params -> get('cr_items', 1).',
                    animateIn: '.(($animateIn = $params -> get('cr_animateIn'))?$animateIn:'false').',
                    animateOut: '.(($animateOut = $params -> get('cr_animateOut'))?$animateOut:'false').',
                    smartSpeed: '.$params -> get('cr_smartSpeed', 250).',
                    margin: '.$params -> get('cr_margin', 0).',
                    mouseDrag: '.($params -> get('cr_mouseDrag', 1)?'true':'false').',
                    touchDrag: '.($params -> get('cr_touchDrag', 1)?'true':'false').',
                    responsive:{
                        '.implode(',', $responsive).'
                    }
                });
            });
        })(jQuery);
    ');
}

require TZ_Portfolio_PlusModuleHelper::getTZLayoutPath($module, $params->get('layout', 'default'));