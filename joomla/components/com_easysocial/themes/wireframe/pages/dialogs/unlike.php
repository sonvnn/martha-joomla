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
<dialog>
	<width>420</width>
	<height>180</height>
	<selectors type="json">
	{
		"{closeButton}"	: "[data-close-button]",
		"{unlikeButton}" : "[data-unlike-button]",
		"{unlikeForm}" : "[data-unlike-page-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_EASYSOCIAL_PAGES_DIALOG_LEAVE_PAGE_TITLE'); ?></title>
	<content>
		<p><?php echo JText::_('COM_EASYSOCIAL_PAGES_DIALOG_LEAVE_PAGE_CONTENT');?></p>

		<form data-unlike-page-form method="post" action="<?php echo JRoute::_('index.php');?>">
			<input type="hidden" name="id" value="<?php echo $page->id;?>" />
			<input type="hidden" name="controller" value="pages" />
			<input type="hidden" name="task" value="unlike" />
			<?php if ($returnUrl) { ?>
				<input type="hidden" name="return" value="<?php echo $returnUrl;?>" />
			<?php } ?>
			<?php echo $this->html('form.token'); ?>
		</form>
	</content>
	<buttons>
		<button data-close-button type="button" class="btn btn-es-default btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CLOSE_BUTTON'); ?></button>
		<button data-unlike-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_('COM_EASYSOCIAL_PAGES_UNLIKE_PAGE'); ?></button>
	</buttons>
</dialog>
