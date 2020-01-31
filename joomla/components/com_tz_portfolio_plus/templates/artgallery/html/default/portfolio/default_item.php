<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    Sonny

# copyright Copyright (C) 2017 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum.html

-------------------------------------------------------------------------*/

defined('_JEXEC') or die();

use Joomla\Utilities\ArrayHelper;

if($this -> items):
    $doc    = JFactory::getDocument();
    $tplParams = TZ_Portfolio_PlusTemplate::getTemplate(true)->params;
?>
    <?php foreach($this -> items as $i => $item):
        $this -> item   = $item;
        $params         = $item -> params;

        if($params -> get('tz_column_width',360))
            $tzItemClass    = ' tz_item';
        else
            $tzItemClass    = null;

        if($item -> featured == 1)
            $tzItemFeatureClass    = ' tz_feature_item';
        else
            $tzItemFeatureClass    = null;

        $class  = '';
        if($params -> get('tz_filter_type','tags') == 'tags'){
            if($item -> tags && count($item -> tags)){
                $alias  = ArrayHelper::getColumn($item -> tags, 'alias');
                $class  = implode(' ', $alias);
            }
        }
        elseif($params -> get('tz_filter_type','tags') == 'categories'){
            $class  = $item -> cat_alias;
            if(isset($item -> second_categories) && $item -> second_categories &&  count($item -> second_categories)) {
                foreach($item -> second_categories as $category){
                    $class  .= ' '.$category -> alias.'_'.$category -> id;
                }
            }
        }
        elseif($params -> get('tz_filter_type','tags') == 'letters'){
            $class  = mb_strtolower(mb_substr(trim($item -> title),0,1));
        }
    ?>
<div id="tzelement<?php echo $item -> id;?>"
     data-date="<?php echo strtotime($item -> created); ?>"
     data-title="<?php echo $this->escape($item -> title); ?>"
     data-hits="<?php echo (int) $item -> hits; ?>"
     data-portfolio-item-id="<?php echo $item -> id; ?>"
     class="element <?php echo $class.$tzItemClass.$tzItemFeatureClass;?>"
     itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">

    <div class="tp-item-box-container"<?php echo trim($tplParams->get('padding')) ? 'style="padding:'.$tplParams->get('padding').'px;"' : ''; ?>>
        <?php if($mediaHtml = $this -> loadTemplate('media')){ ?>
        <div class="tp-thumb">
            <div class="tpFrame"><a href="<?php echo $item ->link; ?>">&nbsp;</a></div>
            <?php
            // Display media from plugin of group tz_portfolio_plus_mediatype
            echo $mediaHtml;
            ?>
        </div>
        <?php } ?>
        <?php
        if(!isset($item -> mediatypes) || (isset($item -> mediatypes) && !in_array($item -> type,$item -> mediatypes))):
            // Start Description and some info
            ?>
            <div class="tpPortfolioDescription">
                <?php
                // Begin Icon print, Email or Edit
                if ($params->get('show_cat_print_icon', 0) || $params->get('show_cat_email_icon', 0)
                    || $params -> get('access-edit')) : ?>
                    <div class="tp-item-tools">
                        <div class="dropdown pull-right float-right" role="presentation">
                            <a class="btn btn-default btn-outline-secondary btn-sm"
                               data-target="#" data-toggle="dropdown"<?php echo $params->get('enable_bootstrap',1) ? ' href="javascript: void(0)"' :''; ?>>
                                <i class="tps tp-cog"></i> <span class="tps tp-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <?php if ($params->get('show_cat_print_icon', 0)) : ?>
                                    <li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $item, $params); ?> </li>
                                <?php endif; ?>
                                <?php if ($params->get('show_cat_email_icon', 0)) : ?>
                                    <li class="email-icon"> <?php echo JHtml::_('icon.email', $item, $params); ?> </li>
                                <?php endif; ?>

                                <?php if ($params -> get('access-edit')) : ?>
                                    <li class="edit-icon"> <?php echo JHtml::_('icon.edit', $item, $params); ?> </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif;
                // End Icon print, Email or Edit
                ?>
                <div class="tpInfo">
                    <?php if($params -> get('show_cat_title',1)): ?>
                        <h3 class="TzPortfolioTitle name" itemprop="name">
                            <?php if($params->get('cat_link_titles',1)) : ?>
                                <a<?php if($params -> get('tz_use_lightbox', 1)){echo ' class="fancybox fancybox.iframe"';}?>
                                    href="<?php echo $item ->link; ?>"  itemprop="url">
                                    <?php echo $this->escape($item -> title); ?>
                                </a>
                            <?php else : ?>
                                <?php echo $this->escape($item -> title); ?>
                            <?php endif; ?>
                        </h3>
                    <?php endif;?>

                    <?php
                    //-- Start display some information --//
                    if ($params->get('show_cat_author',0) or $params->get('show_cat_category',0)) :
                        ?>
                        <div class="muted tpMeta text-muted">
                            <?php echo $item -> event -> beforeDisplayAdditionInfo;?>
                            <?php if ($params->get('show_cat_category',0)) : ?>
                                <div class="tpCategories">
                                    <i class="tp tp-folder-open"></i>
                                    <?php $title = $this->escape($item->category_title);
                                    $url = '<a href="' . $item -> category_link
                                        . '" itemprop="genre">' . $title . '</a>';
                                    $lang_text  = 'COM_TZ_PORTFOLIO_PLUS_CATEGORY';
                                    ?>

                                    <?php if(isset($item -> second_categories) && $item -> second_categories
                                        && count($item -> second_categories)){
                                        $lang_text  = 'COM_TZ_PORTFOLIO_PLUS_CATEGORIES';
                                        foreach($item -> second_categories as $j => $scategory){
                                            if($j <= count($item -> second_categories)) {
                                                $title  .= ', ';
                                                $url    .= ', ';
                                            }
                                            $url    .= '<a href="' . $scategory -> link
                                                . '" itemprop="genre">' . $scategory -> title . '</a>';
                                            $title  .= $this->escape($scategory -> title);
                                        }
                                    }?>

                                    <?php if ($params->get('cat_link_category',1)) : ?>
                                        <?php echo $url; ?>
                                    <?php else : ?>
                                        <?php echo '<span itemprop="genre">' . $title . '</span>'; ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($params->get('show_cat_author', 0) && !empty($item->author )) : ?>
                                    <div class="TzPortfolioCreatedby" itemprop="author" itemscope itemtype="http://schema.org/Person">
                                        <i class="tp tp-user"></i>
                                        <?php $author =  $item->author; ?>
                                        <?php $author = ($item->created_by_alias ? $item->created_by_alias : $author);?>
                                        <?php $author = '<span itemprop="name">' . $author . '</span>'; ?>

                                        <?php if ($params->get('cat_link_author', 1)):?>
                                            <?php 	echo JHtml::_('link', $item -> author_link, $author, array('itemprop' => 'url')); ?>
                                        <?php else :?>
                                            <?php echo JText::sprintf('COM_TZ_PORTFOLIO_PLUS_WRITTEN_BY', $author); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
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
</div>
    <?php endforeach;?>
<?php endif;?>
