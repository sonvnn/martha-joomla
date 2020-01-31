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

class PlgTZ_Portfolio_PlusMediaTypeVideoViewArticle extends JViewLegacy{

    protected $item     = null;
    protected $params   = null;
    protected $video    = null;
    protected $head     = false;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $item           = $this -> item;
		$item 			= $this -> get('Item');

        if($item){
            if($media = $item -> media){
                if(isset($media -> video)){
                    $video  = clone($media -> video);
                    if($size = $params -> get('mt_video_related_thumb_size','o')){
                        if(isset($video -> thumbnail) && !empty($video -> thumbnail)) {
                            $image_url_ext      = JFile::getExt($video->thumbnail);
                            $image_url          = str_replace('.' . $image_url_ext, '_' . $size . '.'
                                                    . $image_url_ext, $video->thumbnail);

                            $video -> related_thumbnail = JURI::root().$image_url;
                        }
                    }
                    if($size = $params -> get('mt_video_thumbnail_size','o')){
                        if(isset($video -> thumbnail) && !empty($video -> thumbnail)) {
                            $image_url_ext      = JFile::getExt($video->thumbnail);
                            $image_url          = str_replace('.' . $image_url_ext, '_' . $size . '.'
                                                    . $image_url_ext, $video->thumbnail);
                            $video -> thumbnail = JURI::root().$image_url;
                            if($this -> getLayout() != 'related') {
                                JFactory::getDocument()->addCustomTag('<meta property="og:image" content="' . $video->thumbnail . '"/>');
                                if($author = $item -> author_info){
                                    JFactory::getDocument() -> setMetaData('twitter:image',$video->thumbnail);
                                }
                            }
                        }
                    }
                    $doc    = JFactory::getDocument();
                    if($video && isset($video -> type)){
                        if($video -> type == 'youtube' || $video -> type == 'vimeo'){

                            $option = null;
                            if($video -> type == 'youtube'){
                                if($video -> title && !empty($video -> title)){
                                    $option = '?title='.urlencode($video -> title);
                                }else{
									$option = '?title='.urlencode($item -> title);
								}
                                if($params -> get('mt_video_autoplay',0)){
                                    if($option && !empty($option)) {
                                        $option    .= '&amp;autoplay=true';
                                    }else{
                                        $option     = '?autoplay=true';
                                    }
                                }
                                $video -> url   = 'https://www.youtube.com/embed/'.$video -> code.$option;
                            }elseif($video -> type == 'vimeo'){
                                if($params -> get('mt_video_autoplay',0)){
                                    $option = '&amp;autoplay=' . $params->get('mt_video_autoplay', 0);
                                }
                                $video -> url   = 'https://player.vimeo.com/video/'.$video -> code
                                    .'?title=0&amp;byline=0&amp;portrait=0&amp;wmode=transparent'.$option;
                            }
                            if($params -> get('mt_video_enable_fluidvid',1)) {
                                if(!$this -> head) {
                                    $doc->addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/video/js/fluidvids.min.js');
                                }
                                if(!$params -> get('mt_video_width')){
                                    $params -> set('mt_video_width',600);
                                }
                                if(!$params -> get('mt_video_height')){
                                    if($video -> type == 'youtube') {
                                        $params->set('mt_video_height', 315);
                                    }
                                    if($video -> type == 'vimeo') {
                                        $params->set('mt_video_height', 255);
                                    }
                                }
                            }
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