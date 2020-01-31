<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="row">
	<div class="col-md-6">

		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYSOCIAL_EVENTS_SETTINGS_LAYOUT'); ?>

			<div class="panel-body">

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_SETTINGS_DEFAULT_AVATAR'); ?>

					<div class="col-md-7">
						<div class="mb-20">
							<div class="es-img-holder">
								<div class="es-img-holder__remove <?php echo !ES::hasOverride('event_avatar') ? 't-hidden' : '';?>">
									<a href="javascript:void(0);" data-image-restore data-type="event_avatar">
										<i class="fa fa-times"></i>
									</a>
								</div>
								<img src="<?php echo ES::getDefaultAvatar('event', 'medium'); ?>" width="64" height="64" data-image-source data-default="<?php echo ES::getDefaultAvatar('event', 'medium', true);?>" />
							</div>
						</div>
						<div style="clear:both;" class="t-lg-mb--xl">
							<input type="file" name="event_avatar" id="event_avatar" class="input" style="width:265px;" data-uniform />
						</div>

						<br />

						<div class="help-block">
							<?php echo JText::_('COM_ES_SETTINGS_DEFAULT_AVATAR_SIZE_NOTICE'); ?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_SETTINGS_DEFAULT_COVER'); ?>

					<div class="col-md-7">
						<div class="mb-20">
							<div class="es-img-holder">
								<div class="es-img-holder__remove <?php echo !ES::hasOverride('event_cover') ? 't-hidden' : '';?>">
									<a href="javascript:void(0);" data-image-restore data-type="event_cover">
										<i class="fa fa-times"></i>
									</a>
								</div>
								<img src="<?php echo ES::getDefaultCover('event'); ?>" width="256" height="98" data-image-source data-default="<?php echo ES::getDefaultCover('event', true);?>" />
							</div>
						</div>

						<div style="clear:both;" class="t-lg-mb--xl">
							<input type="file" name="event_cover" id="event_cover" class="input" style="width:265px;" data-uniform />
						</div>

						<br />

						<div class="help-block">
							<?php echo JText::_('COM_ES_SETTINGS_DEFAULT_COVER_SIZE_NOTICE'); ?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_SETTINGS_DEFAULT_TAB'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'events.item.display', $this->config->get('events.item.display'), array(
									array('value' => 'timeline', 'text' => 'COM_EASYSOCIAL_SETTINGS_DEFAULT_TAB_TIMELINE'),
									array('value' => 'info', 'text' => 'COM_EASYSOCIAL_USERS_SETTINGS_PROFILE_DISPLAY_ABOUT')
								)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_EVENTS_SETTINGS_DEFAULT_EDITOR'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.editors', 'events.editor', $this->config->get('events.editor')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_EVENTS_SETTINGS_SHOW_END_DATE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.showenddate', $this->config->get('events.showenddate')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_EVENTS_SETTINGS_DISPLAY_TIMEZONE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.timezone', $this->config->get('events.layout.timezone')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_EVENTS_SETTINGS_DISPLAY_EVENT_TIME'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.eventtime', $this->config->get('events.layout.eventtime')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_EVENTS_SETTINGS_DISPLAY_TIME_FORMAT'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'events.timeformat', $this->config->get('events.timeformat'), array(
									array('value' => '12h', 'text' => 'COM_ES_SETTINGS_DISPLAY_TIME_FORMAT_12H'),
									array('value' => '24h', 'text' => 'COM_ES_SETTINGS_DISPLAY_TIME_FORMAT_24H')
								)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_EVENTS_SETTINGS_ENABLE_HIT_COUNTER'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.hits', $this->config->get('events.layout.hits')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_THEMES_WIREFRAME_EVENTS_SHOW_ADDRESS'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.address', $this->config->get('events.layout.address')); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_THEMES_WIREFRAME_EVENTS_SHOW_SEATS_LEFT'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.seatsleft', $this->config->get('events.layout.seatsleft')); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_THEMES_WIREFRAME_EVENTS_CATEGORY_HEADERS'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.categoryheaders', $this->config->get('events.layout.categoryheaders')); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_THEMES_WIREFRAME_CLUSTERS_SHOW_DESCRIPTION_LISTINGS'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.listingsdesc', $this->config->get('events.layout.listingsdesc')); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_THEMES_WIREFRAME_CLUSTERS_SHOW_DESCRIPTION'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'events.layout.description', $this->config->get('events.layout.description')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>