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


// No direct access
defined('_JEXEC') or die;

if(isset($item -> donated) && $item -> donated && $params->get('show_donate')): ?>
    <?php

    $currentCode    =   $item->currency->display ? $item->currency->sign : $item->currency->code;
    $doc = JFactory::getDocument();
    $doc->addStyleSheet(TZ_Portfolio_PlusUri::root(true).'/addons/content/charity/css/charity.css');
    $doc -> addStyleSheet(TZ_Portfolio_PlusUri::root(true).'/addons/content/charity/css/style.css');
    ?>
    <div class="item-causes">
        <div class="charity">
            <div class="donate-goal">
                <div class="donate-progress">
                    <?php
                    $donated    = $item -> donated;
                    $donateSum  = (int)$donated["sumDonate"];
                    $goalDonate = (int)$paramArt->get('tz_crt_goal_money');
                    if($donateSum != 0 && $goalDonate != 0) {
                        $tlDonate   = ($donateSum*100)/$goalDonate;
                        if($tlDonate > 100) {
                            $tlDonate = 100;
                        }
                    }else {
                        $tlDonate   = 0;
                    }
                    // check class
                    if($tlDonate == 0) {
                        $clCkDn = ' dno';
                    }elseif($tlDonate == 100) {
                        $clCkDn = ' dyes';
                    }else {
                        $clCkDn = '';
                    }
                    ?>
                    <div class="item-progress">
                        <div class="child-prgb" style="width:<?php echo $tlDonate;?>%;">
                            <div id="prgb_child<?php echo $item -> id;?>" class="wow slideInLeft animated"></div>
                        </div>
                    </div>
                    <?php if($params->get('show_dnted',1) || $params->get('show_gldn',1)):?>

                        <div class="progress-label">
                            <div class="progress-ed">
                                <?php echo JText::_('ADDON_COLLECTED');?>
                                <?php if ($item->currency->position) { ?>
                                    <span><?php echo $donateSum.' '.$currentCode;?></span>
                                <?php } else { ?>
                                    <span><?php echo $currentCode.$donateSum;?></span>
                                <?php }?>
                            </div>
                            <div class="total">
                                <?php echo JText::_('ADDON_DONATOR');?>
                                <span><?php echo $donated["countDonate"];?></span>
                            </div>
                            <div class="progress-final"><?php echo JText::_('ADDON_DONATE_GOAL');?>
                                <?php if ($item->currency->position) { ?>
                                    <span><?php echo $goalDonate.' '.$currentCode;?></span>
                                <?php } else { ?>
                                    <span><?php echo $currentCode.$goalDonate;?></span>
                                <?php }?>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;