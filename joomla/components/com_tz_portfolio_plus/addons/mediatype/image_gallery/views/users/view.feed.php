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

class PlgTZ_Portfolio_PlusMediaTypeImage_GalleryViewUsers extends JViewLegacy{

    protected $item         = null;
    protected $params       = null;
    protected $image_gallery = null;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;
        $item           = $this -> item;

        if(!$item){
            $item = $this -> get('Item');
        }

        if($item){
            if($media = $item -> media){
                if(isset($media -> image_gallery)){

                    if($params -> get('mt_img_gallery_show_feed_image',1)){
                        $image_gallery  = clone($media -> image_gallery);
                        if(isset($image_gallery -> url) && !empty($image_gallery -> url)
                            && count($image_gallery -> url)){
                            $image_gallery -> thumb_url = array();

                            $title = $this->escape($item->title);
                            $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

                            $link = JRoute::_(TZ_Portfolio_PlusHelperRoute::getArticleRoute($item -> slug, $item -> catid, true, -1));

                            foreach($image_gallery -> url as $i => &$url) {
                                $image_url_ext  = JFile::getExt($url);
                                if($size = $params -> get('mt_image_feed_size','o')){
                                    $image_url      = str_replace('.' . $image_url_ext, '_' . $size . '.'
                                                        . $image_url_ext, $url);
                                    $url            = JURI::root() . $image_url;

                                    echo '<a href="'.$link.'"><img src="'.$url.'" alt="'.$title.'"/></a>';
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}