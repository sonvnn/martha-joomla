<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2015 templaza.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

if(isset($item) && $item):
    if($params -> get('show_comment_count', 1) && isset($item -> commentCount)):
        ?>
        <div class="muted">
            <?php echo JText::_('PLG_CONTENT_COMMENT_COUNT');?>
            <span itemprop="commentCount"><?php echo $item -> commentCount;?></span>
        </div>
    <?php endif;
endif;
