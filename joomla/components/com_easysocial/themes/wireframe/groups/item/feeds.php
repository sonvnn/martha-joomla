<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php if ($customFilter) { ?>
	<div class="es-snackbar es-hashtag">
		<div class="es-snackbar__cell">
			<h1 class="es-snackbar__title"><?php echo JText::_($customFilter->title);?></h1>
		</div>
		
		<?php if ($group->canCreateStreamFilter()) { ?>
		<div class="es-snackbar__cell">
			<a href="javascript:void(0);" data-edit-filter data-id="<?php echo $customFilter->id; ?>" data-type="<?php echo $customFilter->utype; ?>" class="t-lg-pull-right">
				<?php echo JText::_('COM_ES_EDIT');?>
			</a>
		</div>
		<?php } ?>
	</div>
<?php } ?>

<?php if (!$customFilter && isset($type) && $type == 'moderation') { ?>
<div class="es-snackbar">
	<div class="es-snackbar__cell">
		<h1 class="es-snackbar__title"><?php echo JText::_('COM_EASYSOCIAL_PENDING_POSTS');?></h1>
	</div>
</div>
<?php } ?>

<?php echo $stream->html(); ?>