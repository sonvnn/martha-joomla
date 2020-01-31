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

if(isset($item) && $item) :
    if (isset($slider) && $slider) {
        ?>
        <div class="tz_portfolio_plus_image_gallery">
            <?php if($params -> get('mt_img_gallery_switcher','image') == 'image'){?>
                <a href="<?php echo $item -> link; ?>">
                    <img src="<?php echo isset($slider->thumbnail) ? $slider->thumbnail : $slider -> url[0]; ?>"
                         alt="<?php echo ($item->title); ?>"
                         title="<?php echo ($item->title); ?>"
                         itemprop="thumbnailUrl"/>
                </a>
            <?php }else{

                $doc    = JFactory::getDocument();
                $doc -> addStyleSheet(TZ_Portfolio_PlusUri::base(true).'/addons/mediatype/image_gallery/css/flexslider.css');
                $doc -> addScript(TZ_Portfolio_PlusUri::base(true).'/addons/mediatype/image_gallery/js/jquery.flexslider-min.js');
                $doc -> addStyleSheet(TZ_Portfolio_PlusUri::base(true).'/addons/mediatype/image_gallery/css/style.css');

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

                if($params -> get('mt_img_gallery_flex_show_arrows',1)) {
                    $dirNav     = 'true';
                }
                if($params -> get('mt_img_gallery_flex_show_controlNav',1)) {
                    $controlNav = 'true';
                    if ($params->get('mt_img_gallery_flex_controlnav_type', 'none') == 'thumbnails' )
                        $controlNav = $params->get('mt_img_gallery_flex_controlnav_type', 'none');
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
                if($params -> get('mt_img_gallery_flex_animation')) {
                    $animation = '\'' . $params->get('mt_img_gallery_flex_animation') . '\'';
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
                $doc -> addScriptDeclaration('
        jQuery(document).ready(function(){
            jQuery(".tz_portfolio_plus_image_gallery .flexslider").flexslider({
                animation: '.$animation.',
                slideDirection: "'.$params -> get('mt_img_gallery_flex_direction').'",
                slideshow: '.$slideshow.',
                slideshowSpeed: '.$params -> get('mt_img_gallery_flex_animSpeed').',
                animationDuration: '.$params -> get('mt_img_gallery_flex_anim_duration').',
                directionNav: '.$dirNav.',
                controlNav: '.(($controlNav=='thumbnails')?'"'.$controlNav.'"':$controlNav).',
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
                itemWidth:'.$params -> get('mt_img_gallery_flex_itemWidth',0).',
                itemMargin:'.$params -> get('mt_img_gallery_flex_itemMargin',0).',
                minItems:'.$params -> get('mt_img_gallery_flex_minItems',0).',
                maxItems:'.$params -> get('mt_img_gallery_flex_maxItems',0).'
            });
        });
    ');

                ?>
                <div class="flexslider">
                    <ul class="slides">
                        <?php foreach($slider -> url as $i => $url):?>
                            <li<?php echo ($controlNav=='thumbnails')?' data-thumb="'.$slider -> thumb_url[$i].'"':''?>>
                                <img src="<?php echo $url;?>"
                                     alt="<?php echo ($slider -> caption[$i])?($slider -> caption[$i]):($item -> title);?>"
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
            <?php }?>
        </div>
        <?php
    }
endif;