<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<span class="es-gif-browser <?php echo $story ? 'is-story' : 'is-comment';?>" data-giphy-browser>
	<span class="es-gif-browser__input-search">
		<input data-giphy-search type="text" class="o-form-control" placeholder="<?php echo JText::_('COM_ES_GIPHY_SEARCH'); ?>">
	</span>
	<span class="es-gif-browser__result">
		<span class="o-loader o-loader--sm" data-giphy-loading></span>
		<?php if ($giphies) { ?>
			<span class="es-gif-browser__result-label" data-giphy-trending><?php echo JText::_('COM_ES_GIPHY_TRENDING'); ?></span>

			<div class="es-gif-list-container" data-giphy-list>
				<?php echo $this->output('site/giphy/browser/list', array('giphies' => $giphies, 'story' => $story)); ?>
			</div>
		<?php } else { ?>
			<span class="es-gif-browser__result-text" data-giphy-no-result><?php echo JText::_('COM_ES_GIPHY_NO_RESULT'); ?></span>
		<?php } ?>
	</span>

	<span class="es-gif-browser__result-footer">
		<span class="es-powered-by-giphy"></span>
	</span>
</span>
