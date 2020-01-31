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
<div class="es-snackbar">
	<div class="es-snackbar__cell">
		<h1 class="es-snackbar__title">
			<?php if ($filter == 'all') { ?>
				<?php echo JText::_('COM_ES_MANAGE_CLUSTER_PENDING_ITEMS'); ?>
			<?php } ?>

			<?php if ($filter == 'event') { ?>
				<?php echo JText::_('COM_EASYSOCIAL_EVENTS'); ?>
			<?php } ?>

			<?php if ($filter == 'group') { ?>
				<?php echo JText::_('COM_EASYSOCIAL_GROUPS' ); ?>
			<?php } ?>

			<?php if ($filter == 'page'){ ?>
				<?php echo JText::_('COM_EASYSOCIAL_PAGES'); ?>
			<?php } ?>
		</h1>
	</div>
</div>

<div class="<?php echo !$clusters ? ' is-empty' : '';?>" data-items>
	<?php if ($clusters) { ?>
		<?php foreach ($clusters as $cluster) { ?>
			<?php echo $this->loadTemplate('site/manage/clusters/item', array('filter' => $filter, 'cluster' => $cluster)); ?>
		<?php } ?>
	<?php } ?>

	<?php echo $this->html('html.loading'); ?>

	<?php echo $this->html('html.emptyBlock', 'COM_ES_CLUSTER_NO_PENDING_MODERATION_' . strtoupper($filter), 'fa-exclamation-circle'); ?>
</div>

<div data-pagination>
	<?php echo $pagination->getListFooter('site');?>
</div>
