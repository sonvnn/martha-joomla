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
    $params     = $this -> params;
    $lang       = JFactory::getLanguage();
    $langcode   = str_replace('-','_',$lang -> getTag());
?>
    <?php if(($params -> get('show_twitter_button',1)) OR ($params -> get('show_facebook_button',1))
        OR ($params -> get('show_google_button',1)) OR $params -> get('show_pinterest_button',1)
        OR $params -> get('show_linkedin_button',1)):?>
        <div class="breadcrumb tz_portfolio_plus_like_button">
            <div class="TzLikeButtonInner">
                <?php
                // Facebook Button
                if($params -> get('show_facebook_button',1)):
                ?>
                    <div class="FacebookButton">
                        <script type="text/javascript">
                            (function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id)) {return;}
                                js = d.createElement(s); js.id = id;
                                js.src = "//connect.facebook.net/<?php echo $langcode;?>/all.js#appId=177111755694317&xfbml=1";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));
                        </script>
                        <div class="fb-like" data-send="false" data-width="200" data-show-faces="true"
                             data-layout="button_count" data-href="<?php echo $this -> item ->fullLink; ?>"></div>
                    </div>
                <?php endif; ?>

                <?php
                // Twitter Button
                if($params -> get('show_twitter_button',1)):
                    ?>
                    <div class="TwitterButton">
                        <a href="https://twitter.com/share" data-url="<?php echo $this -> item ->fullLink; ?>"
                           class="twitter-share-button" data-size="small"></a>
                        <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
                    </div>
                <?php endif; ?>

                <?php
                // Google +1 Button
                if($params -> get('show_google_button',1) == 1):
                    ?>
                    <div class="GooglePlusOneButton">
                        <div class="g-plusone" data-size="medium" data-href="<?php echo $this -> item -> fullLink; ?>"></div>
                        <script type="text/javascript">
                            (function() {
                                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                                po.src = 'https://apis.google.com/js/plusone.js';
                                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                            })();
                        </script>
                    </div>
                <?php endif; ?>

                <?php
                // Pinterest Button
                if($params -> get('show_pinterest_button',1)):
                    ?>
                    <div class="PinterestButton">
                        <a href="//www.pinterest.com/pin/create/button/?url=<?php echo urlencode($this -> item -> fullLink);?>"
                           data-pin-do="buttonPin" data-pin-config="beside"
                           data-pin-color="red"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png"
                                                     alt=""/></a>
                        <script type="text/javascript">
                            (function(d){
                                var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
                                p.type = 'text/javascript';
                                p.async = true;
                                p.src = '//assets.pinterest.com/js/pinit.js';
                                f.parentNode.insertBefore(p, f);
                            }(document));
                        </script>
                    </div>
                <?php endif;?>

                <?php
                // Linkedin Button
                if($params -> get('show_linkedin_button',1)):
                    ?>
                    <!-- Linkedin Button -->
                    <div class="LinkedinButton">
                        <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                        <script type="IN/Share" data-url="<?php echo $this -> item -> fullLink; ?>" data-counter="right"></script>
                    </div>
                <?php endif;?>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php endif; ?>
<?php }