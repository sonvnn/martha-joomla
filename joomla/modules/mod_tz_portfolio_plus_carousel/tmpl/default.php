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

if($list){
?>
<div id="module__<?php echo $module -> id;?>" class="tpp-module__carousel<?php echo $moduleclass_sfx;?>">
    <div class="owl-carousel owl-theme">
        <?php foreach($list as $i => $item){
            // Get article's extrafields
            $extraFields    = TZ_Portfolio_PlusFrontHelperExtraFields::getExtraFields($item, null,
                false, array('filter.list_view' => true, 'filter.group' => $item -> params -> get('order_fieldgroup', 'rdate')));
            $item -> extrafields    = $extraFields;
            ?>
            <div class="item">
                <?php
                if(isset($item->event->onContentDisplayMediaType) && $media = $item -> event -> onContentDisplayMediaType){
                ?>
                <div class="tpp-module-media"><?php echo $media;?></div>
                <?php } ?>
                <?php if(!isset($item -> mediatypes) || (isset($item -> mediatypes) && !in_array($item -> type,$item -> mediatypes))){ ?>
                    <?php if($item -> params -> get('show_title', 1)){ ?>
                    <h3 class="title">
                        <a href="<?php echo $item -> link; ?>"><?php echo $item -> title; ?></a>
                    </h3>
                    <?php } ?>

                    <?php
                    //Call event onContentBeforeDisplay on plugin
                    if(isset($item -> event -> beforeDisplayContent)) {
                        echo $item->event->beforeDisplayContent;
                    }
                    ?>

                    <div class="tpp-module-meta muted">
                        <?php
                        if (isset($item->event->beforeDisplayAdditionInfo)) {
                            echo $item->event->beforeDisplayAdditionInfo;
                        }
                        ?>
                        <?php if($item -> params -> get('show_category_main', 1) || $item -> params -> get('show_category_sec', 1)){ ?>
                        <div class="tpp-module-category">
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
                        <div class="tpp-module-date">
                            <span class="tp tp-clock-o"></span>
                            <?php echo JHtml::_('date', $item -> created, JText::_('DATE_FORMAT_LC'));?>
                        </div>
                        <?php } ?>
                        <?php if($item -> params -> get('show_modified_date', 1)){ ?>
                        <div class="tpp-module-date">
                            <span class="tp tp-pencil-square-o"></span>
                            <?php echo JHtml::_('date', $item -> modified, JText::_('DATE_FORMAT_LC'));?>
                        </div>
                        <?php } ?>
                        <?php if($item -> params -> get('show_publish_date', 1)){ ?>
                        <div class="tpp-module-date">
                            <span class="tp tp-clock-o"></span>
                            <?php echo JHtml::_('date', $item -> publish_up, JText::_('DATE_FORMAT_LC'));?>
                        </div>
                        <?php } ?>
                        <?php if($item -> params -> get('show_author', 1)){ ?>
                        <div class="tpp-module-date">
                            <span class="tp tp-pencil"></span>
                            <a href="<?php echo $item -> authorLink;?>"><?php echo $item -> author;?></a>
                        </div>
                        <?php } ?>

                        <?php
                        if(isset($item -> event -> afterDisplayAdditionInfo)){
                            echo $item -> event -> afterDisplayAdditionInfo;
                        }
                        ?>
                    </div>

                    <?php if($item -> params -> get('show_introtext', 1)){ ?>
                        <div class="tpp-module-description"><?php echo $item -> introtext; ?></div>
                    <?php } ?>
                    <?php
                    if(isset($item -> extrafields) && !empty($item -> extrafields)):
                        ?>
                        <ul class="tz-extrafields">
                            <?php foreach($item -> extrafields as $field):?>
                                <li class="tz_extrafield-item">
                                    <?php if($field -> hasTitle()):?>
                                        <div class="tz_extrafield-label"><?php echo $field -> getTitle();?></div>
                                    <?php endif;?>
                                    <div class="tz_extrafield-value pull-left">
                                        <?php echo $field -> getListing();?>
                                    </div>
                                </li>
                            <?php endforeach;?>
                        </ul>
                        <?php
                    endif;
                    ?>
                    <?php
                    if(isset($item -> event -> contentDisplayListView)) {
                        echo $item->event->contentDisplayListView;
                    }
                    ?>
                    <?php if($item -> params -> get('show_readmore', 1)){ ?>
                        <div class="tpp-module-readmore">
                            <a class="btn" href="<?php echo $item -> link;?>"><?php echo JText::_('MOD_TZ_PORTFOLIO_PLUS_READ_MORE'); ?></a>
                        </div>
                    <?php } ?>
                <?php } ?>
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