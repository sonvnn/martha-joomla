<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusMediaTypeImage_GalleryViewPortfolio extends JViewLegacy{

    protected $item             = null;
    protected $params           = null;
    protected $image_gallery    = null;
    protected $head             = null;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;
		$item 			= $this -> get('Item');

        if($item){
            if($media = $item -> media){
                if(isset($media -> image_gallery)){

                    if($params -> get('mt_img_gallery_switcher','image') == 'gallery'){
                        if(!$this -> head) {
                            $doc = JFactory::getDocument();
                            $doc->addStyleSheet(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/image_gallery/css/flexslider.css');
                            $doc->addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/image_gallery/js/jquery.flexslider-min.js');
                            $this -> head   = true;
                        }
                    }

                    $image_gallery  = clone($media -> image_gallery);
                    if(isset($image_gallery -> url) && !empty($image_gallery -> url)
                        && count($image_gallery -> url)){
                        $image_gallery -> thumb_url = array();

                        $image_gallery->temp        =   $image_gallery->url;
                        foreach($image_gallery -> url as $i => &$url) {
	                        if (preg_match('/media\/tz_portfolio_plus\/article\/cache/i', $url)) {
		                        $image_url_ext  = JFile::getExt($url);
		                        if($thumb_size = $params -> get('mt_img_gallery_thumb_size','o')){
			                        $thumb_url      = str_replace('.' . $image_url_ext, '_' . $thumb_size . '.'
				                        . $image_url_ext, $url);

			                        $thumb_url      = JURI::root() . $thumb_url;
			                        $image_gallery -> thumb_url[$i] = $thumb_url;
		                        }
		                        if($size = $params -> get('mt_cat_img_gallery_size','o')){
			                        $image_url      = str_replace('.' . $image_url_ext, '_' . $size . '.'
				                        . $image_url_ext, $url);

			                        if (isset($image_gallery->featured) && $url == $image_gallery->featured) {
				                        $image_gallery->thumbnail   =   JURI::root() . $image_url;
			                        }
			                        $url            = JURI::root() . $image_url;
		                        }
	                        } else {
		                        if($thumb_size = $params -> get('mt_img_gallery_thumb_size','o')){
			                        if ($thumb_size!='o') {
				                        $thumb_url      =   'images/tz_portfolio_plus/image_gallery/'.$item->id.'/resize/'
					                        . JFile::stripExt($url)
					                        . '_' . $thumb_size . '.' . JFile::getExt($url);
			                        } else {
				                        $thumb_url      =   'images/tz_portfolio_plus/image_gallery/'.$item->id.'/'.$url;
			                        }

			                        $thumb_url      = JURI::root() . $thumb_url;
			                        $image_gallery -> thumb_url[$i] = $thumb_url;
		                        }
		                        $size = $params -> get('mt_cat_img_gallery_size','o');
		                        $tmp        =   $url;
		                        if ($size != 'o') {
			                        $url  =   JURI::root() .'images/tz_portfolio_plus/image_gallery/'.$item->id.'/resize/'
				                        . JFile::stripExt($url)
				                        . '_' . $size . '.' . JFile::getExt($url);
		                        } else {
			                        $url  =   JURI::root() .'images/tz_portfolio_plus/image_gallery/'.$item -> id.'/'.$url;
		                        }
		                        if ( isset($image_gallery->featured) && $tmp == $image_gallery->featured) {
			                        $image_gallery->thumbnail   =   $url;
		                        }
	                        }
                        }
                    }
                    $this -> image_gallery  = $image_gallery;
                }
            }
            $this -> item   = $item;
        }

        parent::display($tpl);
    }
}