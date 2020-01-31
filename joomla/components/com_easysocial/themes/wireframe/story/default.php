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
<div class="es-story <?php echo $showPostTo ? 'is-story-selection' : '';?> <?php echo $fromModule ? 'is-expanded' : 'is-collapsed'; ?>" data-story="<?php echo $story->id;?>" data-story-form data-story-hashtags="<?php echo implode(',', $story->hashtags); ?>" data-story-module="<?php echo $fromModule; ?>">

	<div class="es-story-avatar">
		<div class="o-avatar <?php echo $this->config->get('layout.avatar.style') == 'rounded' ? 'o-avatar--rounded' : '';?>">
			<img src="<?php echo $this->my->getAvatar();?>" title="<?php echo $this->html('string.escape', $this->my->getName());?>" data-story-avatar />
		</div>
	</div>

	<div class="es-story-header">
		<div class="es-story-panel-buttons" data-story-panel-buttons>
			<?php if ($showPostTo) { ?>
			<div class="es-story-panel-button es-story-panel-button--label">
				<a href="javascript:void(0);"
					data-story-selection-button
					data-popbox="module://easysocial/story/selection"
					data-popbox-toggle="click"
					data-popbox-component="popbox--story-select"
					data-popbox-position="bottom-left"
					data-popbox-offset="6"
					class="es-story-avatar-popbox-toggle"
				>
					<div class="es-story-panel-button__label">
						<b data-story-selection-title>
							<div class="o-avatar o-avatar--xs <?php echo $this->config->get('layout.avatar.style') == 'rounded' ? 'o-avatar--rounded' : '';?>">
								<?php if ($cluster) { ?>
									<img src="<?php echo $cluster->getAvatar();?>" title="<?php echo $this->html('string.escape', $cluster->getTitle());?>" width="16" height="16" />
								<?php } else { ?>
									<img src="<?php echo $this->my->getAvatar();?>" title="<?php echo $this->html('string.escape', $this->my->getName());?>" width="16" height="16" />
								<?php } ?>
							</div>&nbsp;

							<?php if ($cluster) { ?>
								<?php echo $cluster->getTitle();?>
							<?php } else { ?>
								<?php echo JText::_('COM_ES_OWN_TIMELINE');?>
							<?php } ?>
						</b>

						<i class="fas fa-caret-down es-story-avatar-popbox-toggle__icon"></i>
					</div>
				</a>
			</div>
			<?php } ?>

			<?php if ($story->panels && $this->config->get('stream.story.enablelimits')) { ?>
			<div class="es-story-panel-button es-story-panel-button--popup"
				data-story-panel-add
				data-popbox data-popbox-id="es"
				data-popbox-type="es-story" data-popbox-toggle="click" data-popbox-component="popbox--story-panel" data-popbox-offset="2" data-popbox-position="bottom-left" data-popbox-target="[data-story-panel-button-dropdown]"
			>
				<div class="es-story-panel-button__shape">
					<i class="fa fa-plus"></i>
				</div>


				<div class="t-hidden" class="es-story-panel-button-popbox" data-story-panel-button-dropdown>
					<div class="popbox-story-panel">
						<div class="popbox-story-panel__hd">
							<div class="popbox-story-panel__title">
								<?php echo JText::_('COM_ES_STORY_WHAT_TO_SHARE');?>
							</div>
							<div class="">
								<?php echo JText::sprintf('COM_ES_STORY_POST_TYPES_QUICK_ACCESS', $this->config->get('stream.story.limit'));?>
							</div>

						</div>
						<div class="popbox-story-panel__bd">

							<div class="es-story-panel-button-list">

								<?php foreach ($story->panels as $panel) { ?>
								<div class="es-story-panel-button-list__item <?php echo $panel->visible ? 'is-selected' : '';?>" data-story-panel-button-more data-id="<?php echo $panel->name;?>">
									<?php if ($this->config->get('stream.story.favourite')) { ?>
									<div class="es-story-panel-button-list__state" data-favourite></div>
									<?php } ?>

									<div class="es-story-panel-button es-story-panel-button--<?php echo $panel->name;?>">
										<?php echo JString::str_ireplace('"tooltip"', '', $panel->button->html);?>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if ($this->isMobile() || $this->isTablet()) { ?>
			<div class="es-story-swiper-nav is-end-left is-end-right" data-story-swiper-nav>
				<div class="es-story-swiper-nav__content swiper-container" data-story-panel-buttons-mobile>
					<div class="swiper-wrapper" data-story-panel-buttons-wrapper>
			<?php } ?>

				<?php if (!$singlePanel || ($singlePanel && $panelType == 'text')) { ?>
				<div class="es-story-panel-button es-story-panel-button--slide active<?php echo $this->isMobile() || $this->isTablet() ? ' swiper-slide' : ''; ?>" data-story-panel-button data-story-plugin-name="text">
					<div class="es-story-panel-button__shape">
						<i class="fa fa-pencil-alt" data-es-provide="tooltip" data-placement="top" data-original-title="<?php echo JText::_('COM_EASYSOCIAL_POST_STATUS');?>"></i>
					</div>
					<span><?php echo JText::_('COM_EASYSOCIAL_STORY_STATUS', true);?></span>
				</div>
				<?php } ?>

				<?php if ($story->panels) { ?>
					<?php foreach ($story->panels as $panel) { ?>
						<?php if ($panel->visible) { ?>
						<div class="es-story-panel-button es-story-panel-button--slide es-story-panel-button--<?php echo $panel->name;?><?php echo $this->isMobile() || $this->isTablet() ? ' swiper-slide' : ''; ?>" data-story-panel-button data-story-plugin-name="<?php echo $panel->name;?>">
							<?php echo $panel->button->html;?>
						</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php if ($this->isMobile() || $this->isTablet()) { ?>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>

		<?php if ($presets && $this->config->get('stream.story.backgrounds')) { ?>
		<div class="es-story-bg-select">
			<div class="o-btn-group">
				<button type="button" class="dropdown-toggle_ es-story-bg-select__dropdown-toggle" data-bs-toggle="dropdown">
					<a href="javascript:void(0);" class="es-story-bg-menu-preview" data-background-current>
						<i class="fa fa-fill-drip"></i>
					</a>
				</button>

				<div class="dropdown-menu dropdown-menu-right es-story-bg-select__dropdown-menu">
					<div class="es-story-bg-menu">
						<?php foreach ($presets as $preset) { ?>
						<div class="es-story-bg-menu__item">
							<a href="javascript:void(0);" class="es-story-bg-menu-preview es-story--bg-<?php echo $preset->id;?>" data-background-select data-id="<?php echo $preset->id;?>">
								<i class="fa fa-fill-drip"></i>
							</a>
						</div>
						<?php } ?>
						<div class="es-story-bg-menu__item">
							<a href="javascript:void(0);" class="es-story-bg-menu-preview es-story-bg-menu-preview--remove" data-background-reset data-preset="0"></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>

	<div class="es-story-body" data-body>
		<div class="es-story-text-placeholder-ie9"><?php echo $placeholderText; ?></div>
		<div class="es-story-text">
			<div class="es-story-textbox mentions-textfield" data-story-textbox>
				<div class="mentions">
					<div data-mentions-overlay data-default="<?php echo $this->html('string.escape', $story->overlay); ?>"><?php echo $story->overlay; ?></div>
					<textarea class="es-story-textfield" name="content" data-story-textField data-mentions-textarea
						data-default="<?php echo $this->html('string.escape', $story->content); ?>"
						data-initial="<?php echo ($story->overlay) ? JString::strlen($story->overlay): '0'; ?>"
						placeholder="<?php echo $placeholderText; ?>"><?php echo $story->content; ?></textarea>
				</div>
				<div>
					<div data-mentions-meta-overlay></div>
				</div>
			</div>
		</div>

		<div class="es-story-panel-content">
			<div class="es-story-panel-contents" data-story-panel-contents>
				<?php foreach ($story->panels as $panel) { ?>
					<div class="es-story-panel-content <?php echo $panel->content->classname; ?> for-<?php echo $panel->name; ?>" data-story-panel-content data-story-plugin-name="<?php echo $panel->name; ?>">
						<?php echo $panel->content->html; ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="es-story-footer" data-footer>

		<?php if ($this->config->get('stream.story.mentions') || $this->config->get('stream.story.location') || $this->config->get('stream.story.moods')) { ?>
		<div class="es-story-meta-contents" data-story-meta-contents>

			<?php if ($this->config->get('stream.story.mentions')) { ?>
			<div class="es-story-meta-content" data-story-meta-content="friends">
				<div class="es-story-friends" data-story-friends>
					<div class="es-story-friends-textbox textboxlist" data-friends-wrapper>
						<input type="text" class="textboxlist-textField" autocomplete="off" placeholder="<?php echo JText::_('COM_EASYSOCIAL_WHO_ARE_YOU_WITH', true); ?>" data-textboxlist-textField />
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if ($this->config->get('stream.story.location')) { ?>
			<div class="es-story-meta-content es-locations" data-story-location data-story-meta-content="location">
				<div id="map-<?php echo $story->id; ?>" class="es-location-map" data-story-location-map>
					<div>
						<img class="es-location-map-image" data-story-location-map-image />
						<div class="es-location-map-actions">
							<button class="btn btn-es-default-o es-location-detect-button" type="button" data-story-location-detect-button>
								<i class="fa fa-map-marker-alt t-text--danger"></i>&nbsp; <?php echo JText::_('COM_EASYSOCIAL_DETECT_MY_LOCATION', true); ?>
							</button>
						</div>
					</div>
				</div>

				<div class="es-location-form" data-story-location-form>
					<div class="es-location-textbox" data-story-location-textbox data-language="<?php echo ES::user()->getLocationLanguage(); ?>">
						<input type="text" class="o-form-control" placeholder="<?php echo JText::_('COM_EASYSOCIAL_WHERE_ARE_YOU_NOW'); ?>" autocomplete="off" data-story-location-textField disabled/>
						<div class="es-location-autocomplete has-shadow is-sticky" data-story-location-autocomplete>
							<b><b></b></b>
							<div class="es-location-suggestions" data-story-location-suggestions></div>
						</div>
					</div>
					<div class="es-location-buttons">
						<div class="o-loader o-loader--sm"></div>
						<a class="es-location-remove-button" href="javascript: void(0);" data-story-location-remove-button>
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if ($this->config->get('stream.story.moods')) { ?>
			<div class="es-story-meta-content es-story-mood is-empty" data-story-mood data-story-meta-content="mood">
				<div class="es-story-mood-form">
					<table class="es-story-mood-textbox" data-story-mood-textbox>
						<tr><td>
							<div class="es-story-mood-verb" data-story-mood-verb>
								<?php foreach ($moods as $mood) { ?>
									<span<?php echo ($mood->key == 'feeling') ? ' class="active"' : ''; ?> data-story-mood-verb-type="<?php echo $mood->key; ?>"><?php echo JText::_($mood->verb); ?></span>
								<?php } ?>
							</div>
						</td>
						<td width="100%">
							<input type="text" class="o-form-control" placeholder="<?php echo JText::_('COM_EASYSOCIAL_HOW_ARE_YOU_FEELING'); ?>" autocomplete="off" data-story-mood-textfield />
						</td>
						</tr>
					</table>
					<div class="es-story-mood-buttons">
						<a class="es-story-mood-remove-button" href="javascript: void(0);" data-story-mood-remove-button><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="es-story-mood-presets" data-story-mood-presets>
					<ul class="g-list-unstyled">
					<?php foreach ($moods as $mood) { ?>
						<?php foreach ($mood->moods as $preset) { ?>
						<li class="es-story-mood-preset"
							data-story-mood-preset
							data-story-mood-icon="<?php echo $preset->icon ?>"
							data-story-mood-verb="<?php echo $mood->key; ?>"
							data-story-mood-subject="<?php echo $preset->key; ?>"
							data-story-mood-text="<?php echo JText::_($preset->text); ?>"
							data-story-mood-subject-text="<?php echo JText::_($preset->subject); ?>"><i class="es-emoji <?php echo $preset->icon; ?>"></i> <?php echo JText::_($preset->subject); ?></li>
						<?php } ?>
					<?php } ?>
					</ul>
				</div>
			</div>
			<?php } ?>

			<?php if (ES::giphy()->isEnabledForStory()) { ?>
			<div class="es-story-meta-content es-story-giphy t-lg-p--md" data-story-meta-content="giphy">
				<div class="es-story-giphy-wrapper" data-giphy-wrapper>
					<div class="o-loader" data-giphy-loader></div>

					<div data-story-giphy>
					</div>
				</div>


				<?php echo $this->output('site/giphy/preview/form', array('giphy' => false)); ?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if ($this->config->get('stream.story.mentions') || $this->config->get('stream.story.location') || $this->config->get('stream.story.moods')) { ?>
		<div class="es-story-meta-buttons">
			<?php if ($this->config->get('stream.story.mentions')) { ?>
			<div class="btn btn-es-default-o es-story-meta-button" data-story-meta-button="friends" data-es-provide="tooltip" data-title="<?php echo JText::_('COM_EASYSOCIAL_STORY_META_PEOPLE');?>">
				<i class="fa fa-user-friends"></i>
			</div>
			<?php } ?>

			<?php if ($this->config->get('stream.story.location')) { ?>
			<div class="btn btn-es-default-o es-story-meta-button" data-story-meta-button="location" data-es-provide="tooltip" data-title="<?php echo JText::_('COM_EASYSOCIAL_STORY_META_LOCATION');?>">
				<i class="fa fa-map-marker-alt"></i>
			</div>
			<?php } ?>

			<?php if ($this->config->get('stream.story.moods')) { ?>
			<div class="btn btn-es-default-o es-story-meta-button" data-story-meta-button="mood" data-es-provide="tooltip" data-title="<?php echo JText::_('COM_EASYSOCIAL_STORY_META_MOOD');?>">
				<i class="fa fa-smile"></i>
			</div>
			<?php } ?>

			<?php if (ES::giphy()->isEnabledForStory()) { ?>
			<div class="btn btn-es-default-o es-story-meta-button" data-story-giphy-meta-button data-story-meta-button="giphy" data-es-provide="tooltip" data-title="<?php echo JText::_('COM_ES_STORY_META_GIPHY');?>">
				<span class="" for="input-gif" data-story-giphy-button>
					GIF
				</span>
			</div>
			<?php } ?>
		</div>
		<?php } ?>

		<div class="es-story-actions <?php echo $story->requirePrivacy() ? '' : ' no-privacy'; ?>">
			<?php if ($story->autoposts) { ?>
			<div class="es-story-actions__share" data-story-autopost>
				<?php foreach ($story->autoposts as $autopost) { ?>
					<?php echo $autopost; ?>
				<?php } ?>
			</div>
			<?php } ?>


			<button class="btn btn-es-primary es-story-submit" data-story-submit type="button"><?php echo JText::_("COM_EASYSOCIAL_STORY_SHARE"); ?></button>
			<?php if ($story->requirePrivacy()) { ?>
			<div class="es-story-privacy" data-story-privacy>
				<?php echo ES::privacy()->form(null, SOCIAL_TYPE_STORY, $this->my->id, 'story.view', true); ?>
			</div>
			<?php } ?>

		</div>

		<?php if ($story->requirePostAs()) { ?>
			<?php //echo $this->html('form.postAs', array('page' => $story->cluster, 'user' => $this->my->id)); ?>
		<?php } ?>

	</div>

	<?php echo $this->html('suggest.hashtags'); ?>
	<?php echo $this->html('suggest.friends'); ?>
	<?php echo $this->html('suggest.emoticons'); ?>

	<?php if ($customParams) { ?>
		<?php foreach ($customParams as $key => $value) { ?>
			<input type="hidden" name="params[<?php echo $key;?>]" value="<?php echo $value;?>" data-story-params />
		<?php } ?>
	<?php } ?>

	<input type="hidden" name="target" data-story-anywhere value="<?php echo $story->getAnywhereId(); ?>" />
	<input type="hidden" name="target" data-story-target value="<?php echo $story->getTarget(); ?>" />
	<input type="hidden" name="cluster" data-story-cluster value="<?php echo $story->getClusterId(); ?>" />
	<input type="hidden" name="clustertype" data-story-clustertype value="<?php echo $story->getClusterType(); ?>" />
	<input type="hidden" name="clusterprivacy" data-story-clusterprivacy value="<?php echo $story->isCluster() ? $story->getCluster()->type : false; ?>" />

	<div class="story-loading"><div class="o-loader is-active"></div></div>
</div>
