<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015-2018 tzportfolio.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

if(isset($item) && $item) :
    if (isset($slider) && $slider) {
        ?>
        <div class="tz_portfolio_plus_image_gallery">
            <img src="<?php echo $slider -> url[0]; ?>"
                 alt="<?php echo ($slider -> caption[0]) ? ($slider -> caption[0]) : ($item->title); ?>"
                 title="<?php echo ($slider -> caption[0]) ? ($slider -> caption[0]) : ($item->title); ?>"
                 itemprop="thumbnailUrl"/>
        </div>
        <?php
    }
endif;