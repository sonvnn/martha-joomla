<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
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
			<?php echo $this->html('panel.heading', 'COM_EASYSOCIAL_GENERAL_SETTINGS'); ?>

			<div class="panel-body">
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_GROUPS_SETTINGS_ENABLE_GROUPS'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'groups.enabled', $this->config->get('groups.enabled')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_SETTINGS_ALLOW_INVITE_NON_FRIENDS'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'groups.invite.nonfriends', $this->config->get('groups.invite.nonfriends')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_CLUSTERS_SETTINGS_FEED_INCLUD_ADMIN'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'groups.feed.includeadmin', $this->config->get('groups.feed.includeadmin')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_EASYSOCIAL_CLUSTERS_SETTINGS_SOCIAL_SHARING_PRIVATE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'groups.sharing.showprivate', $this->config->get('groups.sharing.showprivate')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_ES_GROUPS_SETTINGS_TAG_NONFRIENDS'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'groups.tag.nonfriends', $this->config->get('groups.tag.nonfriends')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
