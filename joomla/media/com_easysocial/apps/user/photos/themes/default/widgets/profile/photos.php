<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php if ($photos) { ?>
<div class="es-side-widget">
	<?php echo $this->html('widget.title', 'APP_PHOTOS_PROFILE_WIDGET_TITLE_PHOTOS'); ?>

	<div class="es-side-widget__bd">
		<?php echo $this->html('widget.photos', $photos); ?>
	</div>

	<?php if ($photos) { ?>
	<div class="es-side-widget__ft">
		<a href="<?php echo ESR::albums(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER));?>" class="es-side-widget-btn-showmore"><?php echo JText::_('COM_ES_VIEW_ALL');?></a>
	</div>
	<?php } ?>
</div>
<?php } ?>
