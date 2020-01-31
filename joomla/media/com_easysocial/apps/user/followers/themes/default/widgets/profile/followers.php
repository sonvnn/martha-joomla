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
<div class="es-side-widget">
	<?php echo $this->html('widget.title', 'APP_FOLLOWERS_WIDGET_TITLE_FOLLOWERS'); ?>

	<div class="es-side-widget__bd">
		<?php echo $this->html('widget.users', $users, 'APP_FOLLOWERS_WIDGET_PROFILE_NO_FOLLOWERS_CURRENTLY'); ?>
	</div>

	<?php if ($users) { ?>
	<div class="es-side-widget__ft">
		<?php echo $this->html('widget.viewAll', 'COM_ES_VIEW_ALL', ESR::followers(array('userid' => $activeUser->getAlias()))); ?>
	</div>
	<?php } ?>
</div>
