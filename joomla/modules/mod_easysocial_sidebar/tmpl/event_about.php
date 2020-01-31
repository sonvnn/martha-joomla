<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div id="es" class="mod-es mod-es-sidebar <?php echo $this->lib->getSuffix();?>">
	<div class="es-sidebar" data-sidebar>
		<?php echo $this->lib->render('widgets', SOCIAL_TYPE_EVENT, 'events', 'sidebarAboutTop', array('uid' => $event->id, 'event' => $event)); ?>

		<?php echo $this->lib->render('module', 'es-events-about-sidebar-top' , 'site/dashboard/sidebar.module.wrapper'); ?>

		<?php echo $this->lib->output('site/events/about/stat', array('event' => $event)); ?>

		<?php echo $this->lib->render('module', 'es-events-about-sidebar-bottom' , 'site/dashboard/sidebar.module.wrapper'); ?>

		<?php echo $this->lib->render('widgets', SOCIAL_TYPE_EVENT, 'events', 'sidebarAboutBottom', array('uid' => $event->id, 'event' => $event)); ?>
	</div>
</div>
