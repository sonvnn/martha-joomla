<?php
/*------------------------------------------------------------------------
# plg_extravote - ExtraVote Plugin
# ------------------------------------------------------------------------
# author    Joomla!Vargas
# copyright Copyright (C) 2010 joomla.vargas.co.cr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.vargas.co.cr
# Technical Support:  Forum - http://joomla.vargas.co.cr/forum
-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

if(isset($this -> item) && $this -> item){
    $params = $this -> params;
    $url = $this->item->link;
?>
    <?php if(($params -> get('show_twitter_button',1)) OR ($params -> get('show_facebook_button',1))
        OR ($params -> get('show_google_button',1)) OR $params -> get('show_pinterest_button',1)
        OR $params -> get('show_linkedin_button',1)) : ?>
        <div class="tz_portfolio_plus_like_button">
            <div class="TzLikeButtonInner">
                <?php
                // Facebook Button
                if($params -> get('show_facebook_button',1)):
                ?>
                    <div class="FacebookButton">
                        <a class="facebook" rel="bookmark" id="fb-share" onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $this->item->title; ?>&amp;p[url]=<?php echo $url; ?>','sharer','toolbar=0,status=0,width=580,height=325');" href="javascript: void(0)">
                        <i class="tp tp-facebook-square"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                // Twitter Button
                if($params -> get('show_twitter_button',1)):
                    ?>
                    <div class="TwitterButton">
                        <a href="javascript:" onclick="popUp=window.open('https://twitter.com/intent/tweet?url=<?php echo $url; ?>&amp;text=<?php echo $this->item->title; ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false" class="tz-social-button tz-twitter">
                            <i class="tp tp-twitter-square"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                // Google +1 Button
                if($params -> get('show_google_button',1) == 1):
                    ?>
                    <div class="GooglePlusOneButton">
                        <a id="g-share" class="st google" onclick="window.open('https://plus.google.com/share?url=<?php echo $url; ?>','sharer','toolbar=0,status=0,width=580,height=325');" href="javascript: void(0)">
                            <i class="tp tp-google-plus-square"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                // Pinterest Button
                if($params -> get('show_pinterest_button',1)):
                    ?>
                    <div class="PinterestButton">
                        <a href="javascript:" onclick="popUp=window.open('http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>', 'popupwindow', 'scrollbars=yes,width=800,height=450'); popUp.focus();return false" class="tz-social-button tz-pinterest">
                            <i class="tp tp-pinterest-square"></i>
                        </a>
                    </div>
                <?php endif;?>

                <?php
                // Linkedin Button
                if($params -> get('show_linkedin_button',1)):
                    ?>
                    <!-- Linkedin Button -->
                    <div class="LinkedinButton">
                        <a href="javascript:" onclick="popUp=window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $url; ?>&amp;title=<?php echo $this->item->title; ?>', 'popupwindow', 'scrollbars=yes,width=1000,height=400'); popUp.focus();return false" class="tz-social-button tz-linkedin">
                            <i class="tp tp-linkedin-square"></i>
                        </a>
                    </div>
                <?php endif;?>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php endif; ?>
<?php }