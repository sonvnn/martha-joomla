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

class PlgTZ_Portfolio_PlusMediaTypeVideoViewPortfolio extends JViewLegacy{

    protected $item     = null;
    protected $params   = null;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $item           = $this -> item;

        if(!$item){
            $item = $this -> get('Item');
        }

        if($item){
            if($media = $item -> media){
                if(isset($media -> video) && $media -> video){
                    $video  = clone($media -> video);

                    if($params -> get('mt_video_show_feed_image',1)){
                        $title = $this->escape($item->title);
                        $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

                        $link = JRoute::_(TZ_Portfolio_PlusHelperRoute::getArticleRoute($item -> slug, $item -> catid, true, -1));

                        if($size = $params -> get('mt_video_feed_size','o')){
                            if(isset($video -> thumbnail) && !empty($video -> thumbnail)) {
                                $image_url_ext      = JFile::getExt($video->thumbnail);
                                $image_url          = str_replace('.' . $image_url_ext, '_' . $size . '.'
                                                        . $image_url_ext, $video->thumbnail);
                                $video -> thumbnail = JURI::root().$image_url;

                                echo '<a href="'.$link.'"><img src="'.$video -> thumbnail.'" alt="'.$title.'"/></a>';
                            }
                        }
                    }

                    if($code = $video -> code){
                        if($video -> type != 'embed'){
                            $item -> description    = '<p>'.htmlspecialchars('{'.$video -> type.'}'.$video -> code.'|'
                                .$params -> get('mt_cat_video_width','100%')
                                .'|'.$params -> get('mt_cat_video_height','auto')
                                .'{/'.$video -> type.'}').'</p>'.$item -> description;
                        }
                    }
                }
            }
        }
    }
}