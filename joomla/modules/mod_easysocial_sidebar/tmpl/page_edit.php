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
<div id="es" class="mod-es mod-es-sidebar <?php echo $this->lib->getSuffix();?>">
	<div class="es-sidebar" data-sidebar>
		<?php echo $this->lib->render('widgets', SOCIAL_TYPE_PAGE, 'pages', 'sidebarEditTop', array('uid' => $page->id, 'page' => $page)); ?>

		<?php echo $this->lib->render('module', 'es-pages-edit-sidebar-top'); ?>

		<div class="es-side-widget">
			<?php echo $this->lib->html('widget.title', 'COM_EASYSOCIAL_PROFILE_SIDEBAR_ABOUT'); ?>

			<div class="es-side-widget__bd">
				<ul class="o-tabs o-tabs--stacked">
					<?php $i = 0; ?>
					<?php foreach ($steps as $step) { ?>
						<li class="o-tabs__item <?php echo ($i == 0 && !$activeStep) || ($activeStep && $activeStep == $step->id) ? ' active' :'';?>" data-page-edit-fields-step data-for="<?php echo $step->id;?>">
							<a href="javascript:void(0);" class="o-tabs__link"><?php echo $step->get('title'); ?>
								<i class=" o-tabs__indicator-error-icon"></i>
							</a>
						</li>
						<?php $i++; ?>
					<?php } ?>

					<?php if ($page->isDraft()) { ?>
						<li class="o-tabs__item" data-page-edit-fields-step data-for="history">
							<a href="javascript:void(0);" class="o-tabs__link"><?php echo JText::_('COM_ES_APPROVAL_HISTORY'); ?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<?php echo $this->lib->render('module', 'es-pages-edit-sidebar-bottom'); ?>

		<?php echo $this->lib->render('widgets', SOCIAL_TYPE_PAGE, 'pages', 'sidebarEditBottom', array('uid' => $page->id, 'page' => $page)); ?>
	</div>
</div>

<script type="text/javascript">
EasySocial
.require()
.script('site/pages/edit')
.done(function($){
	$('body').implement(EasySocial.Controller.Pages.Edit, {
		id: <?php echo $page->id; ?>
	});
});
</script>
