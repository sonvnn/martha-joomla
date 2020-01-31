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

class PlgTZ_Portfolio_PlusMediaTypeVideoViewUsers extends JViewLegacy{

    protected $item     = null;
    protected $params   = null;
    protected $video     = null;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
		$item 			= $this -> get('Item');

        if($item){
            if($media = $item -> media){
                if(isset($media -> video) && $media -> video){
                    $video  = clone($media -> video);
                    if($size = $params -> get('mt_video_thumbnail_size','o')){
                        if(isset($video -> thumbnail) && !empty($video -> thumbnail)) {
                            $image_url_ext      = JFile::getExt($video->thumbnail);
                            $image_url          = str_replace('.' . $image_url_ext, '_' . $size . '.'
                                                    . $image_url_ext, $video->thumbnail);
                            $video -> thumbnail = JURI::root().$image_url;
                        }
                    }

                    $option   = '';
                    if($video && isset($video -> type)){
                        if($video -> type == 'youtube'){
                            if($video -> title){
                                $option = '?title='.urlencode($video -> title);
                            }else{
								$option = '?title='.urlencode($item -> title);
							}
                            if($params -> get('mt_cat_video_autoplay',0)){
                                if($option) {
                                    $option    .= '&amp;autoplay=' . $params->get('mt_cat_video_autoplay', 0);
                                }else{
                                    $option     = '?autoplay=' . $params->get('mt_cat_video_autoplay', 0);
                                }
                            }
                            $video -> url   = 'https://www.youtube.com/embed/'.$video -> code.$option.'&amp;time='.time();
                        }elseif($video -> type == 'vimeo'){
                            if($params -> get('mt_cat_video_autoplay',0)){
                                $option = '&amp;autoplay=' . $params->get('mt_cat_video_autoplay', 0);
                            }
                            $video -> url   = 'https://player.vimeo.com/video/'.$video -> code
                                .'?title=0&amp;byline=0&amp;portrait=0&amp;wmode=transparent'.$option
                                .'&amp;time='.time();
                        }
                    }

                    $this -> video  = $video;
                }
            }
            $this -> item   = $item;
        }
        $this -> params = $params;

        parent::display($tpl);
    }
}