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
<div class="es-snackbar">
	<div class="es-snackbar__cell">
		<h1 class="es-snackbar__title"><?php echo JText::_('COM_EASYSOCIAL_FRIENDS_INVITES_HEADING'); ?></h1>
	</div>
	<div class="es-snackbar__cell t-text--right">
		<a href="<?php echo FRoute::friends(array('layout' => 'invite'));?>">
			<?php echo JText::_('COM_EASYSOCIAL_INVITE_FRIEND_BUTTON');?>
		</a>
	</div>
</div>

<div class="<?php echo !$friends ? ' is-empty' : '';?>" data-items>
	<?php if ($friends){ ?>
		<div class="es-list">
			<?php foreach ($friends as $user) { ?>
			<div class="es-list__item">
				<div class="es-list-item es-island" data-item data-id="<?php echo $user->id;?>">

					<div class="es-list-item__media">
						<div class="o-avatar-status">
							<a href="javascript:void(0);" class="o-avatar">
								<img src="https://gravatar.com/avatar/<?php echo md5($user->email);?>" alt="<?php echo $this->html('string.escape' , $user->email);?>" />
							</a>
						</div>
					</div>

					<div class="es-list-item__context">
						<div class="es-list-item__hd">
							<div class="es-list-item__content">

								<div class="es-list-item__title">
									<a href="javascript:void(0);"><?php echo $user->email;?></a>

								</div>

								<div class="es-list-item__meta">
									<ol class="g-list-inline g-list-inline--delimited">
										<li data-invitation-info>
											<?php if ($user->registered_id) { ?>
												<?php echo JText::sprintf('COM_EASYSOCIAL_FRIENDS_INVITED_REGISTERED_AS', $this->html('html.user', $user->registered_id)); ?>
											<?php } else { ?>
												<?php echo JText::sprintf('COM_EASYSOCIAL_FRIENDS_INVITED_ON', '<b>' . $this->html('string.date', $user->created, JText::_('DATE_FORMAT_LC2')) . '</b>'); ?>
											<?php } ?>
										</li>
									</ol>
								</div>
							</div>


							<div class="es-list-item__action">
								<div class="dropdown_">
									<button type="button" class="dropdown-toggle_ btn btn-es-default-o btn-sm" data-bs-toggle="dropdown">
										<i class="fa fa-ellipsis-v"></i>
									</button>

									<ul class="dropdown-menu dropdown-menu-right">
										<?php if (!$user->registered_id) { ?>
										<li>
											<a href="javascript:void(0);" data-es-invitation-resend>
												<?php echo JText::_('COM_EASYSOCIAL_FRIENDS_INVITED_RESEND');?>
											</a>
										</li>
										<?php } ?>

										<li>
											<a href="javascript:void(0);" data-es-invitation-delete>
												<?php echo JText::_('COM_EASYSOCIAL_FRIENDS_INVITED_DELETE');?>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>


					</div>

				</div>
			</div>

			<?php } ?>
		</div>
	<?php } ?>

	<?php echo $this->html('html.emptyBlock', 'COM_EASYSOCIAL_FRIENDS_NO_INVITES_SENT', 'fa-users'); ?>
</div>


<?php echo $pagination->getListFooter('site');?>

