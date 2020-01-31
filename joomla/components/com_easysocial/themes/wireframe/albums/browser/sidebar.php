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
<?php if ($albums) { ?>
	<?php foreach ($albums as $album) { ?>
	<li class="o-tabs__item has-notice <?php if ($album->id == $albumId) { ?> active <?php } ?>" data-album-list-item data-album-id="<?php echo $album->id; ?>">
		<a href="<?php echo $album->getPermalink(); ?>"
			title="<?php echo $this->html('string.escape', $album->get('title')); ?>"
			class="o-tabs__link"
			custom-title="<?php echo $album->getPageTitle(); ?>"
		>

			<i data-album-list-item-cover style="background-image: url(<?php echo $album->getCover(); ?>);"></i>
			<span data-album-list-item-title><?php echo $album->get('title'); ?></span>
			<div class="o-tabs__bubble" data-album-list-item-count><?php echo $album->getTotalPhotos(); ?></div>
		</a>
	</li>
	<?php } ?>
<?php } ?>
