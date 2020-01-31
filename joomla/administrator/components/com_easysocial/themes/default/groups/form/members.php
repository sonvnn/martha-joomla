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
<div data-groups-form-members>
	<div class="panel-table">
		<table class="app-table table">
			<thead>
				<tr>
					<th width="1%" class="center">
						<input type="checkbox" name="toggle" data-table-grid-checkall />
					</th>

					<th>
						<?php echo $this->html('grid.sort', 'username', JText::_('COM_EASYSOCIAL_USERS_NAME'), $ordering, $direction); ?>
					</th>

					<th width="5%" class="center">
						<?php echo $this->html('grid.sort', 'state', JText::_('COM_EASYSOCIAL_TABLE_COLUMN_ENABLED'), $ordering, $direction); ?>
					</th>

					<th width="15%" class="center">
						<?php echo JText::_('COM_EASYSOCIAL_TABLE_COLUMN_ROLE');?>
					</th>

					<th width="20%" class="center">
						<?php echo $this->html('grid.sort', 'username', JText::_('COM_EASYSOCIAL_USERS_USERNAME'), $ordering, $direction); ?>
					</th>

					<th width="5%" class="center">
						<?php echo $this->html('grid.sort', 'id', JText::_('COM_EASYSOCIAL_USERS_ID'), $ordering, $direction); ?>
					</th>
				</tr>
			</thead>

			<tbody>
			<?php if (!empty($members)) { ?>
				<?php $i = 0; ?>
				<?php foreach ($members as $member) { ?>
					<?php $user = ES::user($member->uid); ?>
					<tr>
						<td><?php echo $this->html('grid.id', $i, $member->id); ?></td>

						<td style="text-align: left;">
							<a href="<?php echo FRoute::_('index.php?option=com_easysocial&view=users&layout=form&id=' . $user->id);?>"
								data-user-insert
								data-id="<?php echo $user->id;?>"
								data-alias="<?php echo $user->getAlias();?>"
								data-title="<?php echo $this->html('string.escape', $user->name);?>"
								data-avatar="<?php echo $this->html('string.escape', $user->getAvatar(SOCIAL_AVATAR_MEDIUM));?>"
							>
								<?php echo $user->name;?>
							</a>
						</td>

						<td class="center">
							<?php echo $this->html('grid.published', $member, 'groups', 'state', array('publishUser', 'unpublishUser')); ?>
						</td>

						<td class="center">
							<?php if ($member->isOwner()) { ?>
								<span class="o-label o-label--primary-o"><?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_OWNER'); ?></span>
							<?php } ?>

							<?php if (!$member->isOwner() && $member->isAdmin()) { ?>
								<span class="o-label o-label--warning-o"><?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_ADMIN'); ?></span>
							<?php } ?>

							<?php if (!$member->isOwner() && !$member->isAdmin()) { ?>
								<span class="o-label o-label--success-o"><?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_MEMBER'); ?></span>
							<?php } ?>
						</td>

						<td class="center">
							<span><?php echo $user->username;?></span>
						</td>

						<td class="center">
							<?php echo $user->id;?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="5">
						<div class="footer-pagination"><?php echo $pagination->getListFooter();?></div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>


<div id="toolbar-members" class="btn-wrapper t-hidden" data-members-dropdown>
	<div class="dropdown">
		<button type="button" class="btn btn-small dropdown-toggle" data-toggle="dropdown">
			<span class="icon-users"></span> <?php echo JText::_('COM_EASYSOCIAL_BUTTON_MEMBERS');?> &nbsp;<span class="caret"></span>
		</button>

		<ul class="dropdown-menu">
			<li>
				<a href="javascript:void(0);" data-cluster-add-member>
					<?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_ADD_MEMBER'); ?>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" data-cluster-remove-member>
					<?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_REMOVE_MEMBER'); ?>
				</a>
			</li>
			<li class="divider">
			</li>
			<li>
				<a href="javascript:void(0);" data-cluster-approve-member>
					<?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_APPROVE_MEMBER'); ?>
				</a>
			</li>
			<li class="divider">
			</li>
			<li>
				<a href="javascript:void(0);" data-cluster-promote-member>
					<?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_PROMOTE_TO_ADMIN'); ?>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" data-cluster-demote-member>
					<?php echo JText::_('COM_EASYSOCIAL_GROUPS_MEMBERS_REMOVE_ADMIN'); ?>
				</a>
			</li>
		</ul>
	</div>
</div>