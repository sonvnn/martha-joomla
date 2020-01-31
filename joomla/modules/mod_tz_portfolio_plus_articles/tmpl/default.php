<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author   TuanNATemPlaza

# copyright Copyright (C) 2015 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

if ($list):
    $doc    = JFactory::getDocument();
	$doc->addStyleSheet('components/com_tz_portfolio_plus/css/all.min.css');
    $doc->addStyleSheet('modules/mod_tz_portfolio_plus_articles/css/style.css');
?>
<div class="mod-tpp-portfolio__article<?php echo $moduleclass_sfx; ?>">
    <ul class="tz_portfolio_plus_articles">
        <?php foreach ($list as $i => $item) : ?>

        <li<?php if ($i == 0) echo ' class="first"'; if ($i == count($list) - 1) echo ' class="last"' ?>>
            <?php if(!isset($item -> mediatypes) || (isset($item -> mediatypes) && !in_array($item -> type,$item -> mediatypes))){?>
            <?php if($params -> get('show_media', 1) && isset($item->event->onContentDisplayMediaType)){ ?>
                <div class="tzpp_media"><?php echo $item->event->onContentDisplayMediaType;?></div>
            <?php } ?>
                <div class="tzpp_content<?php if(!($params -> get('show_media', 1) && isset($item->event->onContentDisplayMediaType))) echo ' w-100'; ?>">
                    <?php if ($params->get('show_title',1)) {?>
                        <h3 class="title"><a href="<?php echo $item->link?>"><?php echo $item->title?></a></h3>
                    <?php } ?>

                    <?php
                    //Call event onContentBeforeDisplay on plugin
                    if(isset($item -> event -> beforeDisplayContent)) {
                        echo $item->event->beforeDisplayContent;
                    }
                    if($params -> get('show_author', 1) or $params->get('show_created_date', 1)
                        or $params->get('show_hit', 1) or $params->get('show_tag', 1)
                        or $params->get('show_category', 1)
                        or !empty($item -> event -> beforeDisplayAdditionInfo)
                        or !empty($item -> event -> afterDisplayAdditionInfo)) {
                        ?>
                        <div class="muted item-meta">

                            <?php
                            if (isset($item->event->beforeDisplayAdditionInfo)) {
                                echo $item->event->beforeDisplayAdditionInfo;
                            }
                            if ($params->get('show_author', 1)) { ?>
                                <div class="tz_created_by"><span
                                            class="text"><i class="tps tp-user"></i>
                        </span><a href="<?php echo $item->author_link; ?>"><?php echo $item->user_name; ?></a></div>
                            <?php } ?>

                            <?php if ($params->get('show_created_date', 1)) { ?>
                                <div class="tz_date">
                        <span class="text"><i class="tps tp-clock"></i>
                        </span><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC3')); ?></div>
                            <?php } ?>

                            <?php if ($params->get('show_hit', 1)) { ?>
                                <div class="tz_hit">
                                <span
                                        class="text"><i class="tps tp-eye"></i></span><?php echo $item->hits; ?>
                                </div>
                            <?php }

                            if ($params->get('show_tag', 1)) {
                                if (isset($tags[$item->content_id])) {
                                    ?>
                                    <div class="tz_tag">
                                    <span
                                            class="text"><i class="tps tp-tag"></i></span>
                                        <?php foreach ($tags[$item->content_id] as $t => $tag) { ?>
                                            <a href="<?php echo $tag->link; ?>"><?php echo $tag->title; ?></a>
                                            <?php if ($t != count($tags[$item->content_id]) - 1) {
                                                echo ', ';
                                            }
                                        } ?>
                                    </div>
                                <?php }
                            }

                            if ($params->get('show_category', 1)) {
                                if (isset($categories[$item->content_id]) && $categories[$item->content_id]) {
                                    ?>
                                    <div class="tz_categories">
                                    <span
                                            class="text"><i class="tps tp-folder"></i></span>
                                        <?php foreach ($categories[$item->content_id] as $c => $category) { ?>
                                            <a href="<?php echo $category->link; ?>"><?php echo $category->title; ?></a>
                                            <?php if ($c != count($categories[$item->content_id]) - 1) {
                                                echo ', ';
                                            }
                                        } ?>
                                    </div>
                                <?php }
                            }

                            if (isset($item->event->afterDisplayAdditionInfo)) {
                                echo $item->event->afterDisplayAdditionInfo;
                            } ?>
                        </div>

                        <?php
                    }
                    if ($params->get('show_introtext',1)) {
                        ?>
                        <div class="description"><?php echo $item->introtext;?></div>
                    <?php }

                    if(isset($item -> event -> contentDisplayListView)) {
                        echo $item->event->contentDisplayListView;
                    }

                    if($params -> get('show_readmore',1)){
                        ?>
                        <a href="<?php echo $item->link?>"
                           class="btn btn-primary btn-sm readmore"><?php echo $params -> get('readmore_text','Read More');?></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php if($params -> get('show_view_all', 0)){?>
        <div class="tpp-portfolio__action text-center">
            <a href="<?php echo $params -> get('view_all_link');?>"<?php echo ($target = $params -> get('view_all_target'))?' target="'
                .$target.'"':'';?> class="btn btn-primary btn-view-all"><?php
                echo $params -> get('view_all_text', 'View All Portfolios');?></a>
        </div>
    <?php } ?>
</div>
<?php endif; ?>