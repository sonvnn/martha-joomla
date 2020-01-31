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
        $class  = null;
        if($params -> get('tz_use_lightbox',0)){
            $class=' class = "fancybox fancybox.iframe"';
        }

        $doc    = JFactory::getDocument();
?>
<div class="tz_portfolio_plus_image_gallery">
    <?php
    $dirNav         = 'false';
    $controlNav     = 'false';
    $pausePlay      = 'false';
    $pauseOnAction  = 'false';
    $pauseOnHover   = 'false';
    $useCSS         = 'false';
    $slideshow      = 'false';
    $animationLoop  = 'false';
    $smoothHeight   = 'false';
    $randomize      = 'false';
    $animation      = '\'fade\'';
    $itemWidth      = 0;

    if($params -> get('mt_img_gallery_flex_show_arrows',1)) {
        $dirNav     = 'true';
    }
    if($params -> get('mt_img_gallery_flex_animation')) {
        $animation =  $params->get('mt_img_gallery_flex_animation');
    }
    if($params -> get('mt_img_gallery_flex_itemWidth',0)){
        $itemWidth  = $params -> get('mt_img_gallery_flex_itemWidth',0);
    }
    if($params -> get('mt_img_gallery_flex_show_controlNav',1)) {
        $controlNav = 'true';
        if ($params->get('mt_img_gallery_flex_controlnav_type', 'none') != 'none' ) {
            $controlNav = $params->get('mt_img_gallery_flex_controlnav_type', 'none');
            $animation  = 'slide';
        }
    }
    if($params -> get('mt_img_gallery_flex_pausePlay',1)) {
        $pausePlay = 'true';
    }
    if($params -> get('mt_img_gallery_flex_pauseOnAction',1)){
        $pauseOnAction   = 'true';
    }
    if($params -> get('mt_img_gallery_flex_pauseOnHover',1)){
        $pauseOnHover   = 'true';
    }
    if($params -> get('mt_img_gallery_flex_useCSS',1)){
        $useCSS   = 'true';
    }
    if($params -> get('mt_img_gallery_flex_slideshow',1)){
        $slideshow  = 'true';
    }
    if($params -> get('mt_img_gallery_flex_animLoop',1)){
        $animationLoop  = 'true';
    }
    if($params -> get('mt_img_gallery_flex_smoothHeight',1)){
        $smoothHeight   = 'true';
    }
    if($params -> get('mt_img_gallery_flex_randomize',0)){
        $randomize  = 'true';
    }
    $doc        = JFactory::getDocument();
    $script     = array();
    if($controlNav == 'thumbnail_slider'){
        $script[]   = '$(".tz_portfolio_plus_image_gallery #img_gallery_carousel'.$item -> id.'").flexslider({
                            animation: "'.$animation.'",
                            controlNav: false,
                            animationLoop: '.$animationLoop.',
                            prevText: "'.JText::_('JPREVIOUS').'",
                            nextText: "'.JText::_('JNEXT').'",
                            pausePlay: '.$pausePlay.',
                            pauseText: "'.JText::_('PLG_MEDIATYPE_IMAGE_GALLERY_PAUSE').'",
                            playText: "'.JText::_('PLG_MEDIATYPE_IMAGE_GALLERY_PLAY').'",
                            pauseOnAction: '.$pauseOnAction.',
                            pauseOnHover: '.$pauseOnHover.',
                            slideshow: '.$slideshow.',
                            asNavFor: "#img_gallery_slide'.$item -> id.'",
                            itemWidth: '.$itemWidth.',
                            itemMargin:'.$params -> get('mt_img_gallery_flex_itemMargin',0).'
                          });';
    }
    $script[]   = '$(".tz_portfolio_plus_image_gallery #img_gallery_slide'.$item -> id.'").flexslider({
                        animation: "'.$animation.'",
                        slideDirection: "'.$params -> get('mt_img_gallery_flex_direction').'",
                        slideshow: '.$slideshow.',
                        slideshowSpeed: '.$params -> get('mt_img_gallery_flex_animSpeed').',
                        animationDuration: '.$params -> get('mt_img_gallery_flex_anim_duration').',
                        directionNav: '.$dirNav.',
                        controlNav: '.(($controlNav=='thumbnails')?'"'.$controlNav.'"':($controlNav == 'thumbnail_slider'?'false':$controlNav)).',
                        prevText: "'.JText::_('JPREVIOUS').'",
                        nextText: "'.JText::_('JNEXT').'",
                        pausePlay: '.$pausePlay.',
                        pauseText: "'.JText::_('PLG_MEDIATYPE_IMAGE_GALLERY_PAUSE').'",
                        playText: "'.JText::_('PLG_MEDIATYPE_IMAGE_GALLERY_PLAY').'",
                        pauseOnAction: '.$pauseOnAction.',
                        pauseOnHover: '.$pauseOnHover.',
                        useCSS: '.$useCSS.',
                        startAt: '.$params -> get('mt_img_gallery_flex_startAt',0).',
                        animationLoop: '.$animationLoop.',
                        smoothHeight: '.$smoothHeight.',
                        randomize: '.$randomize.',
                        sync: "'.($controlNav == 'thumbnail_slider'?'#img_gallery_carousel'.$item -> id:'').'",
                        itemWidth: '.($controlNav == 'thumbnail_slider'?0:$itemWidth).',
                        itemMargin:'.$params -> get('mt_img_gallery_flex_itemMargin',0).',
                        minItems:'.$params -> get('mt_img_gallery_flex_minItems',0).',
                        maxItems:'.$params -> get('mt_img_gallery_flex_maxItems',0).'
                    });';

    $doc -> addScriptDeclaration('
        (function($){
            $(document).ready(function(){
                '.(implode("\n",$script)).'
            });
        })(jQuery);
    ');

    ?>
    <div id="img_gallery_slide<?php echo $item -> id;?>" class="flexslider">
        <ul class="slides">
            <?php foreach($slider -> url as $i => $url):?>
                <li<?php echo ($controlNav=='thumbnails')?' data-thumb="'.$slider -> thumb_url[$i].'"':''?>>
                    <a class="image-gallery-zoom" data-thumb="<?php echo $slider -> thumb_url[$i]; ?>" data-id="image<?php echo $i; ?>" href="<?php echo $url; ?>"><i class="tps tp-search"></i></a>
                    <img src="<?php echo $url;?>"
                         alt="<?php echo ($slider -> caption[$i])?($slider -> caption[$i]):($this -> item -> title);?>"
                        <?php if(!empty($slider -> caption[$i])):?>
                            title="<?php echo $slider -> caption[$i];?>"
                        <?php endif; ?>
                        />

                    <?php
                    if($slider -> caption[$i]):
                        ?>
                        <p class="flex-caption"><?php echo $slider -> caption[$i];?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php if($controlNav == 'thumbnail_slider'):?>
    <div id="img_gallery_carousel<?php echo $item -> id;?>" class="flexslider carousel">
        <ul class="slides">
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
                        <p class="flex-caption"><?php echo $slider -> caption[$i];?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif;?>
</div>
<?php
    }
}