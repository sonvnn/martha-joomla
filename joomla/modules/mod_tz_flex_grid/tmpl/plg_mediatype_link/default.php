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

if(isset($item) && $item){
    if(isset($link) && $link){
?>
<h3 class="title">
    <i class="icon-link"></i>
    <a href="<?php echo $link -> url?>"
       rel="<?php echo $link -> follow;?>"
       target="<?php echo $link -> target?>"><?php echo $link -> title;?></a>
</h3>
<?php
    if ($params->get('show_introtext',1)) {
        echo $item->introtext;
    }

    }
}