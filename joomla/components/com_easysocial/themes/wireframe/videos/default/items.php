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

<?php if ($activeCategory) { ?>
<div class="t-lg-mb--xl">
	<?php echo $this->html('miniheader.videoCategory', $activeCategory); ?>
</div>
<?php } ?>

<?php if ($featuredVideos && isset($featuredOutput) && $featuredOutput) { ?>
	<?php echo $featuredOutput; ?>
<?php } ?>

<?php if ($browseView) { ?>
<div class="es-list-sorting-wrapper">
	<div class="es-list-sorting">
		<?php echo $this->html('form.popdown', 'sorting', $sort, array(
			$this->html('form.popdownOption', 'latest', 'COM_ES_SORT_BY_LATEST', '', false, $sortItems->latest->attributes, $sortItems->latest->url),
			$this->html('form.popdownOption', 'alphabetical', 'COM_ES_SORT_BY_ALPHABETICALLY', '', false, $sortItems->alphabetical->attributes, $sortItems->alphabetical->url),
			$this->html('form.popdownOption', 'popular', 'COM_ES_SORT_BY_POPULARITY', '', false, $sortItems->popular->attributes, $sortItems->popular->url),
			$this->html('form.popdownOption', 'commented', 'COM_ES_SORT_BY_MOST_COMMENTED', '', false, $sortItems->commented->attributes, $sortItems->commented->url),
			$this->html('form.popdownOption', 'likes', 'COM_ES_SORT_BY_MOST_LIKES', '', false, $sortItems->likes->attributes, $sortItems->likes->url)
		)); ?>
	</div>
</div>
<?php } ?>

<?php echo $this->output('site/videos/default/items.header'); ?>

<div class="es-list-result">
	<?php echo $this->html('listing.loader', 'card', 4, 2, array('snackbar' => true)); ?>

	<div data-result-list>
		<?php echo $this->loadTemplate('site/videos/default/item.list', array('videos' => $videos, 'pagination' => $pagination, 'uid' => $uid, 'type' => $type, 'browseView' => $browseView, 'from' => $from, 'returnUrl' => $returnUrl)); ?>
	</div>
</div>
