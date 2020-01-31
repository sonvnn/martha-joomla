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
    <?php if($params -> get('mt_audio_switcher','thumbnail') == 'thumbnail'){
        ?>
    <a href="<?php echo $item -> link; ?>">
        <img src="<?php echo $audio -> thumbnail; ?>"
             title="<?php echo ($audio -> caption) ? ($audio -> caption) : ($item->title); ?>"
             alt="<?php echo ($audio -> caption) ? ($audio -> caption) : ($item->title); ?>"/>
    </a>
    <?php }else{
        if($audio -> type == 'embed'){
            echo $audio -> embed_code;
        }else{
            ?>
    <iframe width="<?php echo $params -> get('mt_audio_soundcloud_width','100%');?>"
            height="<?php echo $params -> get('mt_audio_soundcloud_height');?>"
            src="<?php echo $audio -> url;?>"
            frameborder="0" allowfullscreen>
    </iframe>
        <?php }
    }
    ?>
</div>
        <?php }
    }
endif;