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
$tplParams = $tzTemplate->params;
$doc = JFactory::getDocument();
if($list){
?>
<div id="module__<?php echo $module -> id;?>" class="tplSelena tpp-bootstrap tpp-module-carousel tpp-module__carousel<?php echo $moduleclass_sfx;?>">
    <div class="owl-carousel owl-theme element">
        <?php foreach($list as $i => $item){ ?>
            <?php
	        $itemparams         =   new JRegistry();
	        $itemparams         =   $itemparams->loadString($item -> attribs);
	        $type           =   $itemparams -> get('tpl_selena_type','standard');
	        $title_pos      =   $itemparams -> get('tpl_selena_title_position','middle');
            ?>
            <div class="TzInner" style="height: <?php echo $tplParams->get('module_carousel_height','450px'); ?>">
                <div class="tpThumbnail <?php echo $title_pos; ?>"<?php echo trim($tplParams->get('padding')) ? 'style="top:'.$tplParams->get('padding').';left:'.$tplParams->get('padding').';right:'.$tplParams->get('padding').';bottom:'.$tplParams->get('padding').';"' : ''; ?>>
                    <div class="TzPortfolioDescription">
                        <?php if(!isset($item -> mediatypes) || (isset($item -> mediatypes) && !in_array($item -> type,$item -> mediatypes))){ ?>
                            <?php if($item -> params -> get('show_title', 1)){ ?>
                                <h3 class="TzPortfolioTitle">
                                    <a href="<?php echo $item -> link; ?>"><?php echo $item -> title; ?></a>
                                </h3>
                            <?php } ?>

                            <?php
                            //Call event onContentBeforeDisplay on plugin
                            if(isset($item -> event -> beforeDisplayContent)) {
                                echo $item->event->beforeDisplayContent;
                            }
                            ?>

                            <div class="tpMeta muted">
                                <?php
                                if (isset($item->event->beforeDisplayAdditionInfo)) {
                                    echo $item->event->beforeDisplayAdditionInfo;
                                }
                                ?>
                                <?php if($item -> params -> get('show_category_main', 1) || $item -> params -> get('show_category_sec', 1)){ ?>
                                    <div class="TZcategory-name">
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
                                <?php } ?>
                                <?php if($item -> params -> get('show_created_date', 1)){ ?>
                                    <div class="TzPortfolioDate">
                                        <span class="tp tp-clock-o"></span>
                                        <?php echo JHtml::_('date', $item -> created, JText::_('DATE_FORMAT_LC'));?>
                                    </div>
                                <?php } ?>
                                <?php if($item -> params -> get('show_modified_date', 1)){ ?>
                                    <div class="TzPortfolioModified">
                                        <span class="tp tp-pencil-square-o"></span>
                                        <?php echo JHtml::_('date', $item -> modified, JText::_('DATE_FORMAT_LC'));?>
                                    </div>
                                <?php } ?>
                                <?php if($item -> params -> get('show_publish_date', 1)){ ?>
                                    <div class="published">
                                        <span class="tp tp-clock-o"></span>
                                        <?php echo JHtml::_('date', $item -> publish_up, JText::_('DATE_FORMAT_LC'));?>
                                    </div>
                                <?php } ?>
                                <?php if($item -> params -> get('show_author', 1)){ ?>
                                    <div class="TzPortfolioCreatedby">
                                        <span class="tp tp-pencil"></span>
                                        <a href="<?php echo $item -> authorLink;?>"><?php echo $item -> author;?></a>
                                    </div>
                                <?php } ?>
                                <?php
                                if ($item -> params->get('show_hit', 1)) {
	                                ?>
                                    <div class="TzPortfolioHits">
                                        <i class="tp tp-eye"></i>
		                                <?php echo $item->hits; ?>
                                        <meta itemprop="interactionCount" content="UserPageVisits:<?php echo $item->hits; ?>" />
                                    </div>
	                                <?php
                                }
                                ?>
                                <?php
                                if(isset($item -> event -> afterDisplayAdditionInfo)){
                                    echo $item -> event -> afterDisplayAdditionInfo;
                                }
                                ?>
                            </div>

                            <?php if($item -> params -> get('show_introtext', 1)){ ?>
                                <div class="TzPortfolioIntrotext"><?php echo $item -> introtext; ?></div>
                            <?php } ?>
                            <?php
                            if(isset($item -> event -> contentDisplayListView)) {
                                echo $item->event->contentDisplayListView;
                            }
                            ?>
                            <?php if($item -> params -> get('show_readmore', 1)){ ?>
                                <div class="tpp-module-readmore">
                                    <a class="btn btn-light" href="<?php echo $item -> link;?>"><?php echo JText::_('MOD_TZ_PORTFOLIO_PLUS_READ_MORE'); ?></a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="overlay"></div>
                    <?php if(isset($item->event->onContentDisplayMediaType) && $media = $item -> event -> onContentDisplayMediaType){ ?>
                        <div class="TzArticleMedia"><?php echo $media;?></div>
                    <?php } ?>
                </div>
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