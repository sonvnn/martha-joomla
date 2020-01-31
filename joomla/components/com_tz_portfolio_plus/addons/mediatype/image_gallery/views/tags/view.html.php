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

class PlgTZ_Portfolio_PlusMediaTypeImage_GalleryViewTags extends JViewLegacy{

    protected $item             = null;
    protected $params           = null;
    protected $image_gallery    = null;
    protected $head             = false;

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

                        $image_gallery->temp        = $image_gallery->url;
                        foreach($image_gallery -> url as $i => &$url) {
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
                                $url            = JURI::root() . $image_url;
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