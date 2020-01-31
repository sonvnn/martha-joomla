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
    <?php echo $this->html('widget.title', 'COM_EASYSOCIAL_EVENTS_RANDOM_EVENTS'); ?>

    <div class="es-side-widget__bd">
        <?php if ($events) { ?>
        <div class="o-flag-list">
            <?php foreach ($events as $event) { ?>
            <div class="o-flag">
                <div class="o-flag__image">
                    <div class="o-avatar-status">
                        <a href="<?php echo $event->getPermalink();?>" class="o-avatar">
                            <img src="<?php echo $event->getAvatar();?>" alt="<?php echo $this->html('string.escape', $event->getName());?>" />
                        </a>
                    </div>
                </div>
                <div class="o-flag__body">
                    <a href="<?php echo $event->getPermalink();?>" class="ed-user-name t-mb--sm"><?php echo $event->getName();?></a>
                    <div class="t-text--muted">
                        <i class="fa fa-users"></i> <?php echo JText::sprintf(ES::string()->computeNoun('COM_EASYSOCIAL_EVENTS_GUESTS', $event->getTotalGuests()), $event->getTotalGuests());?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div>
            <?php echo JText::_('COM_EASYSOCIAL_EVENTS_NO_EVENTS_FOUND'); ?>
        </div>
        <?php } ?>
    </div>
</div>