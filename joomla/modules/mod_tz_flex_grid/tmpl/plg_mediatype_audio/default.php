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

// No direct access
defined('_JEXEC') or die;

if($params -> get('mt_show_audio',1)):
    if(isset($item) && $item){
        if (isset($audio) && $audio) {
?>
<div class="tz_audio">
    <a href="<?php echo $item -> link; ?>">
        <img src="<?php echo $audio -> thumbnail; ?>"
             title="<?php echo ($audio -> caption) ? ($audio -> caption) : ($item->title); ?>"
             alt="<?php echo ($audio -> caption) ? ($audio -> caption) : ($item->title); ?>"/>
    </a>
</div>
        <?php }
    }
endif;
?>