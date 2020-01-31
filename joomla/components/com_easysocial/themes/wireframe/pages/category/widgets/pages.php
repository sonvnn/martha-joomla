<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="es-side-widget">
    <?php echo $this->html('widget.title', 'COM_EASYSOCIAL_PAGES_RANDOM_PAGES'); ?>

    <div class="es-side-widget__bd">
        <?php if ($pages) { ?>
        <div class="o-flag-list">
            <?php foreach ($pages as $page) { ?>
            <div class="o-flag">
                <div class="o-flag__image">
                    <div class="o-avatar-status is-online">
                        <a href="<?php echo $page->getPermalink();?>" class="o-avatar">
                            <img src="<?php echo $page->getAvatar();?>" alt="<?php echo $this->html('string.escape', $page->getName());?>" />
                        </a>
                    </div>
                </div>
                <div class="o-flag__body">
                    <a href="<?php echo $page->getPermalink();?>" class="ed-user-name t-mb--sm"><?php echo $page->getName();?></a>
                    <div class="t-text--muted">
                        <i class="fa fa-users"></i> <?php echo $page->getTotalMembers(); ?><?php echo JText::sprintf(ES::string()->computeNoun('COM_EASYSOCIAL_PAGES_FOLLOWERS', $page->getTotalMembers()));?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div class="t-text--muted">
            <?php echo JText::_('COM_EASYSOCIAL_PAGES_NO_PAGES_YET'); ?>
        </div>
        <?php } ?>
    </div>
</div>