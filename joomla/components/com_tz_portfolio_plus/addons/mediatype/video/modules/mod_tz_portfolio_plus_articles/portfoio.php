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

if(isset($item) && $item):
    if (isset($video) && $video) {
        ?>
        <div class="tz_portfolio_plus_video">
            <?php if($params -> get('mt_video_switcher','thumbnail') == 'thumbnail'){
                ?>
                <a href="<?php echo $item -> link; ?>">
                    <img src="<?php echo $video -> thumbnail; ?>"
                         title="<?php echo ($video -> title) ? ($video -> title) : ($item->title); ?>"
                         alt="<?php echo ($video -> title) ? ($video -> title) : ($item->title); ?>"
                         itemprop="thumbnailUrl"/>
                </a>
            <?php }else{
                if($video -> type == 'embed'){
                    echo $video -> embed_code;
                }else{
                    ?>

                    <iframe src="<?php echo $video -> url;?>"
                            width="<?php echo $params -> get('mt_video_width');?>"
                            height="<?php echo $params -> get('mt_video_height');?>"
                            frameborder="0" allowFullScreen itemprop="embedUrl">
                    </iframe>
                <?php }
            }
            ?>
        </div>
        <?php
    }
endif;