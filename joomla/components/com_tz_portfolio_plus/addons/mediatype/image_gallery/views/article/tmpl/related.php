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
$slider = $this->image_gallery;
$params = $this -> params;

if($item && $slider && isset($slider -> url) && !empty($slider -> url)):

    if($params -> get('mt_img_gallery_related_show_image',1)):
        ?>
        <a<?php echo $params -> get('tz_use_lightbox', 1)?' class="fancybox fancybox.iframe"':'';?>
            href="<?php echo $this -> item -> link; ?>">
            <img src="<?php echo $slider -> url[0]; ?>"
                 alt="<?php echo ($slider -> caption[0]) ? ($slider -> caption[0]) : ($this->item->title); ?>"
                 title="<?php echo ($slider -> caption[0]) ? ($slider -> caption[0]) : ($this->item->title); ?>"
                 itemprop="thumbnailUrl"/>
        </a>
    <?php
    endif;
endif;