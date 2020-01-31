<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Carousel Module

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2018 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
$tzTemplate = TZ_Portfolio_PlusTemplate::getTemplateById($params -> get('template_id'));
$doc = JFactory::getDocument();
if($list){
?>
<div id="module__<?php echo $module -> id;?>" class="tplArtGallery tpp-bootstrap tpp-module-carousel tpp-module__carousel<?php echo $moduleclass_sfx;?>">
    <div class="owl-carousel owl-theme element">
        <?php foreach($list as $i => $item){
	        ?>
            <div class="tp-item-box-container">
                <?php
                if(isset($item->event->onContentDisplayMediaType) && ($mediaHtml = $item->event->onContentDisplayMediaType)){
                    ?>
                <div class="tp-thumb">
                    <div class="tpFrame"><a href="<?php echo $item ->link; ?>">&nbsp;</a></div>
                    <div class="TzArticleMedia">
                        <?php echo $mediaHtml;?>
                    </div>
                </div>
                <?php
            } ?>
	            <?php
	            if(!isset($item -> mediatypes) || (isset($item -> mediatypes) && !in_array($item -> type,$item -> mediatypes))):
		            // Start Description and some info
		            ?>
                    <div class="tpPortfolioDescription">
                        <div class="tpInfo">
				            <?php
				            if ($item -> params -> get('show_title', 1)) {
					            echo '<h3 class="TzPortfolioTitle"><a href="' . $item->link . '">' . $item->title . '</a></h3>';
				            }
				            //Call event onContentBeforeDisplay on plugin
				            if(isset($item -> event -> beforeDisplayContent)) {
					            echo $item->event->beforeDisplayContent;
				            }
				            ?>

				            <?php
				            //-- Start display some information --//
				            if ($item -> params->get('show_author',0) or $item -> params->get('show_category',0)) :
					            ?>
                                <div class="muted tpMeta">
						            <?php echo $item -> event -> beforeDisplayAdditionInfo;?>
						            <?php if ($item -> params->get('show_category',0)) : ?>
							            <?php
							            if($item -> params -> get('show_category_main', 1) || $item -> params -> get('show_category_sec', 1)){ ?>
                                            <div class="tpCategories">
                                                <span class="tp tp-folder-open"></span>
									            <?php if($item -> params -> get('show_category_main', 1)){ ?>
                                                    <a href="<?php echo $item -> category_link; ?>"><?php echo $item -> category_title;
										            ?></a><?php
									            }
									            if($item -> params -> get('show_category_sec', 1) && $item -> second_categories
										            && count($item -> second_categories)){
										            foreach($item -> second_categories as $secCategory){
											            ?><span class="tpp-module__carousel-separator">,</span>
                                                        <a href="<?php echo $secCategory -> link; ?>"><?php echo $secCategory -> title; ?></a>
										            <?php }
									            } ?>
                                            </div>
							            <?php }
							            ?>

						            <?php endif; ?>
						            <?php if ($item -> params->get('show_author', 0)) : ?>
                                        <div class="TzPortfolioCreatedby">
                                            <span class="tp tp-user"></span>
                                            <a href="<?php echo $item -> authorLink;?>"><?php echo $item -> author;?></a>
                                        </div>
						            <?php endif; ?>
						            <?php if(isset($item -> event -> afterDisplayAdditionInfo)){
							            echo $item -> event -> afterDisplayAdditionInfo;
						            } ?>
                                </div>
					            <?php
				            endif;
				            //-- End display some information --//
				            ?>
				            <?php echo $item -> event -> contentDisplayListView; ?>
                        </div>
                    </div>
		            <?php
		            // End Description and some info
	            endif;?>
            </div>
        <?php } ?>
    </div>
    <?php if($params -> get('show_view_all', 0)){?>
        <div class="tpp-portfolio__action text-center">
            <a href="<?php echo $params -> get('view_all_link');?>"<?php echo ($target = $params -> get('view_all_target'))?' target="'
                .$target.'"':'';?> class="btn btn-primary btn-view-all"><?php
                echo $params -> get('view_all_text', 'View All Portfolios');?></a>
        </div>
    <?php } ?>
</div>
<?php
}