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

// No direct access
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusMediaTypeVideo extends TZ_Portfolio_PlusPlugin
{
    protected $autoloadLanguage = true;

    public function onContentDisplayMediaType($context, &$article, $params, $page = 0, $layout = null){
        if($article){
            if($media = $article -> media){
                if(isset($media -> video)){
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
                            }
                            if($params -> get('mt_video_autoplay',0)){
                                if($option) {
                                    $option    .= '&amp;autoplay=' . $params->get('mt_video_autoplay', 0);
                                }else{
                                    $option     = '?autoplay=' . $params->get('mt_video_autoplay', 0);
                                }
                            }
                            $video -> url   = 'http://www.youtube.com/embed/'.$video -> code.$option.'&amp;time='.time();
                        }elseif($video -> type == 'vimeo'){
                            if($params -> get('mt_video_autoplay',0)){
                                $option = '&amp;autoplay=' . $params->get('mt_video_autoplay', 0);
                            }
                            $video -> url   = 'http://player.vimeo.com/video/'.$video -> code
                                .'?title=0&amp;byline=0&amp;portrait=0&amp;wmode=transparent'.$option
                                .'&amp;time='.time();
                        }
                    }
                    $this -> setVariable('video', $video);
                }
            }
            $this -> setVariable('item', $article);

            return parent::onContentDisplayMediaType($context, $article, $params, $page, $layout);
        }
    }
}