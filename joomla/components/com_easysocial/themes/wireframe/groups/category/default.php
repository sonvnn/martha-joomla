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
<div class="es-group-category">

	<?php echo $this->html('header.groupcategory', $category); ?>

	<div class="es-container t-lg-mt--xl" data-es-groups-category data-es-container>

		<!-- do not remove this element. This element is needed for the stream loodmore to work properly -->
		<div data-filter-item
			data-type="groupcategory"
			data-id="<?php echo $category->id;?>"
			class="active"
		></div>

		<?php echo $this->html('html.sidebar'); ?>

		<?php if ($this->isMobile()) { ?>
			<?php echo $this->includeTemplate('site/groups/category/mobile'); ?>
		<?php } ?>

		<div class="es-content">
			<div class="es-snackbar">
				<h1 class="es-snackbar__title"><?php echo JText::_('COM_EASYSOCIAL_GROUPS_RECENT_UPDATES'); ?></h1>
			</div>

			<div class="es-content-wrap" data-es-group-item-content>
				<?php echo $stream->html();?>
			</div>
		</div>
	</div>
</div>
