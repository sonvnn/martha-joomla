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
<div class="<?php echo !$events && !$delayed && empty($featuredEvents) ? 'is-empty' : '';?>">
	<div class="<?php echo $this->isMobile() ? 'es-list' : 'es-cards es-cards--2';?>">
		<?php foreach($events as $event){ ?>
			<?php echo $this->html('listing.event', $event, array(
					'showDistance' => $showDistance,
					'isGroupOwner' => isset($isGroupOwner) ? $isGroupOwner : false,
					'style' => $this->isMobile() ? 'listing' : 'card',
					'browseView' => $browseView
				)); ?>
		<?php } ?>
	</div>

	<?php echo $pagination->getListFooter('site'); ?>

	<?php echo $this->html('html.emptyBlock', $emptyText, 'far fa-calendar-alt'); ?>
</div>
