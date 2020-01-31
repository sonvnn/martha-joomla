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
<dialog>
	<width>600</width>
	<height>340</height>
	<selectors type="json">
	{
		"{closeButton}": "[data-close-button]",
		"{submitButton}": "[data-submit-button]",
		"{form}": "[data-es-filter-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function() {
			this.parent.close();
		},

		"{submitButton} click" : function() {
			this.form().submit();
		}
	}
	</bindings>
	<title><?php echo JText::_($filter->id ? 'COM_ES_EDITING_FILTER' : 'COM_ES_AUDIO_CUSTOM_FILTER_HEADER'); ?></title>
	<content>
		<form method="post" action="<?php echo JRoute::_('index.php');?>" class="es-forms" data-es-filter-form>
			<div class="es-forms__group">
				<div class="es-forms__content">
					<div class="es-stream-filter-content">
						<p class="t-lg-mb--xl t-lg-mt--xl"><?php echo JText::_('COM_ES_AUDIO_CUSTOM_FILTER_DESC'); ?></p>

						<div class="alert alert-danger t-hidden" data-notice></div>

						<div class="o-form-group" data-title style="margin-top: 30px;">
							<input type="text" name="title" class="o-form-control"
								placeholder="<?php echo JText::_('COM_ES_AUDIO_CUSTOM_FILTER_TITLE_PLACEHOLDER', true); ?>"
								value="<?php echo $this->html('string.escape', $filter->title); ?>"
							/>

							<div class="help-block text-note"><?php echo JText::_('COM_ES_AUDIO_CUSTOM_FILTER_TITLE_DESC'); ?></div>
						</div>

						<div class="o-form-group" data-hashtag>
							<input type="text" name="hashtag" value="<?php echo $filter->getHashTag(true); ?>" class="o-form-control"
								placeholder="<?php echo JText::_('COM_ES_AUDIO_CUSTOM_FILTER_HASHTAG_PLACEHOLDER', true); ?>"
							/>

							<div class="help-block text-note"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_CUSTOM_FILTER_HASHTAG_DESC'); ?></div>
						</div>

						<?php if ($filter->id) { ?>
						<div class="o-checkbox">
							<input type="checkbox" id="delete-filter" name="delete" value="1" />
							<label for="delete-filter" class="t-text--danger"><?php echo JText::_('COM_ES_DELETE_CUSTOM_FILTER'); ?></label>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<?php echo $this->html('form.action', 'tag', 'saveFilter'); ?>
			<?php echo $this->html('form.hidden', 'filterType', 'audios'); ?>
			<?php echo $this->html('form.hidden', 'clusterType', $clusterType); ?>
			<?php echo $this->html('form.hidden', 'cid', $cid); ?>
			<?php echo $this->html('form.hidden', 'id', $filter->id); ?>
		</form>
	</content>
	<buttons>
		<button type="button" class="btn btn-es-default btn-sm" data-close-button><?php echo JText::_('COM_ES_CANCEL');?></button>
		<button type="button" class="btn btn-es-primary-o btn-sm" data-submit-button><?php echo JText::_($filter->id ? 'COM_ES_UPDATE' : 'COM_ES_CREATE_BUTTON');?></button>
	</buttons>
</dialog>
