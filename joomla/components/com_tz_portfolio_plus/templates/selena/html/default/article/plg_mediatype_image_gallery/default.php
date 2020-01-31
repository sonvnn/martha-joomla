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

$params = $this->params;
if($item   = $this -> item) {
    if (isset($this->image_gallery) && $slider = $this->image_gallery) {
        $doc    = JFactory::getDocument();
?>
<div class="tz_portfolio_plus_image_gallery">
    <ul class="image-list">
        <?php foreach($slider -> url as $i => $url):?>
            <li>
                <img src="<?php echo $url;?>"
                     alt="<?php echo ($slider -> caption[$i])?($slider -> caption[$i]):($this -> item -> title);?>"
                    <?php if(!empty($slider -> caption[$i])):?>
                        title="<?php echo $slider -> caption[$i];?>"
                    <?php endif; ?>
                />

                <?php
                if($slider -> caption[$i]):
                    ?>
                    <p class="caption muted"><?php echo $slider -> caption[$i];?></p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
    }
}