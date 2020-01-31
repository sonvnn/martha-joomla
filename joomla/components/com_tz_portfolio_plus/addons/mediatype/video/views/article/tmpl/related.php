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

$item   = $this -> item;
$params = $this -> params;

if($params -> get('mt_video_related_show_thumbnail',1) && $item   = $this -> item) :
    if (isset($this->video) && $video = $this->video) :
        if(isset($video -> related_thumbnail) && !empty($video -> related_thumbnail)):
        ?>
        <div class="TzImage">
            <a<?php if($params -> get('tz_use_lightbox',0) == 1){echo ' class="fancybox fancybox.iframe"';}?>
                href="<?php echo $item -> link;?>">
                <img src="<?php echo $video -> related_thumbnail;?>"
                     title="<?php echo ($video -> title) ? ($video -> title) : ($item->title); ?>"
                     alt="<?php echo ($video -> title) ? ($video -> title) : ($item->title); ?>"/>
            </a>
        </div>
        <?php
        endif;
    endif;
endif;