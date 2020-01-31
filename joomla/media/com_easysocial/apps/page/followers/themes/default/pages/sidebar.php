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
<div id="es" class="mod-es mod-es-sidebar-page-follower <?php echo $moduleLib->getSuffix();?>"
	data-es-member-filter
	data-cluster-id="<?php echo $cluster ? $cluster->id : '' ?>"
	data-cluster-type="<?php echo $cluster ? $cluster->getType() : '' ?>"
>
	<div class="es-sidebar" data-sidebar>
		<?php echo $this->render('module', 'es-page-follower-sidebar-top', 'site/dashboard/sidebar.module.wrapper'); ?>

		<div class="es-side-widget">
			<?php echo $this->html('widget.title', 'COM_ES_FILTERS'); ?>

			<div class="es-side-widget__bd">
				<ul class="o-tabs o-tabs--stacked feed-items">
					<li class="o-tabs__item has-notice <?php echo $active == '' ? ' active' : '';?>" data-filter data-type="all">
						<a href="javascript:void(0);" class="o-tabs__link">
							<?php echo JText::_('APP_PAGE_FOLLOWERS_FILTER_ALL');?>
						</a>
						<div class="o-tabs__bubble" data-counter><?php echo $counters->total;?></div>
					</li>

					<li class="o-tabs__item has-notice <?php echo $active == 'followers' ? ' active' : '';?>" data-filter data-type="followers">
						<a href="javascript:void(0);" class="o-tabs__link">
							<?php echo JText::_('APP_PAGE_FOLLOWERS_FILTER_FOLLOWERS');?>
						</a>
						<div class="o-tabs__bubble" data-counter><?php echo $counters->followers;?></div>
					</li>

					<li class="o-tabs__item has-notice <?php echo $active == 'admin' ? ' active' : '';?>" data-filter data-type="admin">
						<a href="javascript:void(0);" class="o-tabs__link">
							<?php echo JText::_('APP_PAGE_FOLLOWERS_FILTER_ADMINS');?>
						</a>
						<div class="o-tabs__bubble" data-counter><?php echo $counters->admins;?></div>
					</li>

					<?php if ($cluster->isClosed() && ($cluster->isAdmin($this->my->id) || $cluster->isOwner($this->my->id))) { ?>
					<li class="o-tabs__item has-notice <?php echo $active == 'pending' ? ' active' : '';?> <?php echo $counters->pending ? 'has-notice' : '';?>" data-filter data-type="pending">
						<a href="javascript:void(0);" class="o-tabs__link">
							<?php echo JText::_('APP_PAGE_FOLLOWERS_FILTER_PENDING');?>
							<div class="o-tabs__bubble" data-counter><?php echo $counters->pending;?></div>
						</a>
					</li>
					<?php } ?>

				</ul>
			</div>
		</div>

		<?php echo $this->render('module', 'es-page-follower-sidebar-bottom', 'site/dashboard/sidebar.module.wrapper'); ?>
	</div>
</div>

<script type="text/javascript">

EasySocial.require()
.script('site/members/filter')
.done(function($) {
	$('body').implement(EasySocial.Controller.Members.Filter);
});

</script>
