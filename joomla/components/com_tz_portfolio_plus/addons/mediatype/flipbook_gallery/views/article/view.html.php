<?php
/*------------------------------------------------------------------------

# Flipbook Gallery Addon

# ------------------------------------------------------------------------

# author    Sonny

# copyright Copyright (C) 2019 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusMediaTypeFlipbook_GalleryViewArticle extends JViewLegacy{

    protected $item             = null;
    protected $params           = null;
    protected $flipbook_gallery    = null;
    protected $head             = false;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;
		$item 			= $this -> get('Item');

        if($item){
            if($media = $item -> media){
                if(isset($media -> flipbook_gallery)){
                	$gallery    =   $media->flipbook_gallery;
					$doc = JFactory::getDocument();
                    if(!$this -> head) {
	                    $doc -> addStyleSheet('components/com_tz_portfolio_plus/css/all.min.css');
	                    $doc -> addStyleSheet('components/com_tz_portfolio_plus/css/jquery.fancybox.min.css');
	                    $doc -> addScript('components/com_tz_portfolio_plus/js/jquery.fancybox.min.js');
	                    $doc -> addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/flipbook_gallery/js/jquery-ui-1.10.4.min.js');
	                    $doc -> addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/flipbook_gallery/js/jquery.easing.1.3.js');
	                    $doc -> addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/flipbook_gallery/js/jquery.booklet.latest.min.js');
	                    $doc -> addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/flipbook_gallery/js/lightbox.min.js');
                        $doc -> addStyleSheet(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/flipbook_gallery/css/style.css');
                        $option         =   array();
                        $option[]       =   "hashTitleText: '".JText::_('PLG_MEDIATYPE_FLIPBOOK_GALLERY_PAGE')."'";
                        $option[]       =   "autoCenter: true";
	                    $width          =   $params->get('mt_flipbook_gallery_width','100%');
	                    $height         =   $params->get('mt_flipbook_gallery_height','');
	                    $ratio          =   $params->get('mt_flipbook_gallery_ratio','5:3');
	                    $direction      =   $params->get('mt_flipbook_gallery_direction','LTR');
	                    $paddingPage    =   $params->get('mt_flipbook_gallery_padding','2vw');
	                    $arrows         =   $params->get('mt_flipbook_gallery_show_arrow',1);
	                    $pageSelector   =   $params->get('mt_flipbook_gallery_show_page_selector',1);
	                    if ($width) $option[]       =   "width: '$width'";
	                    if ($height) $option[]      =   "height: '$height'";
	                    if ($ratio) {
		                    list($rwidth,$rheight)  =   explode(':', $ratio);
	                    	$option[]   =   "rwidth: $rwidth";
	                    	$option[]   =   "rheight: $rheight";
	                    }
						if ($direction) $option[]   =   "direction: '$direction'";
	                    if ($paddingPage) $option[] =   "pagePadding: '$paddingPage'";
	                    if ($arrows) $option[]      =   "arrows: true";
	                    if ($pageSelector) {
	                    	$option[]               =   "menu: '#flipbook-gallery-menu'";
	                    	$option[]               =   "pageSelector: true";
	                    }

	                    $doc -> addScriptDeclaration("
	                    jQuery(function($) {
	                        $(document).ready(function () {
	                            $('.tz_portfolio_plus_flipbook_gallery').booklet({
	                                ".implode(',', $option)."
	                            });
	                        });
	                    });
	                    ");
	                    $lightboxopt    =   $params->get('flipbook_gallery_lightbox_option',['zoom', 'slideShow', 'fullScreen', 'thumbs', 'close']);
	                    if (is_array($lightboxopt)) {
		                    for ($i = 0 ; $i< count($lightboxopt); $i++) {
			                    $lightboxopt[$i]  =   '"'.$lightboxopt[$i].'"';
		                    }
	                    }

	                    $lightboxopt=   is_array($lightboxopt) ? implode(',', $lightboxopt) : '';

	                    $doc -> addScriptDeclaration('var flipbook_gallery_lightbox_buttons = ['.$lightboxopt.'];');
                        $this -> head   =   true;
                    }

	                $this -> flipbook_gallery  = clone($media -> flipbook_gallery);
                }
            }
            $this -> item   = $item;
        }

        parent::display($tpl);
    }

}