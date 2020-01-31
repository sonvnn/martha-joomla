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
    if (isset($this->video) && $video = $this->video) {
        $doc    = JFactory::getDocument();
?>
<div class="tz_portfolio_plus_video">
    <?php
    if($video -> type == 'embed'){
        echo $video -> embed_code;
    }else{

        $video_url      = '';
        $video_width    = $params -> get('mt_video_width');
        $video_height   = $params -> get('mt_video_height');

        if($params -> get('mt_video_enable_fluidvid',1)) {
            $doc->addScriptDeclaration('
                (function($){
                    $(document).ready(function(){
                        fluidvids.init({
                            selector: [\'.tz_portfolio_plus_video iframe\'],
                            players: [\'www.youtube.com\', \'player.vimeo.com\']
                        });
                    });
                })(jQuery);');
        }
    ?>

        <iframe src="<?php echo $video -> url;?>"
                width="<?php echo $params -> get('mt_video_width');?>"
                height="<?php echo $params -> get('mt_video_height');?>"
                frameborder="0" allowFullScreen itemprop="embedUrl">
        </iframe>
    <?php }
    ?>
</div>
<?php
    }
}