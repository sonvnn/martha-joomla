<?php
/**
 * @package   Astroid Framework
 * @author    JoomDev https://www.joomdev.com
 * @copyright Copyright (C) 2009 - 2019 JoomDev.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

defined('_JEXEC') or die;
?>
<div class="latestnews menu list-inline">
	<ul class="menu list-inline">
		<?php foreach ($list as $item) : $image = json_decode($item->images); ?>
		<li itemscope itemtype="https://schema.org/Article">
			<a class="article-title" href="<?php echo $item->link; ?>" itemprop="url">
				<span itemprop="name">
					<?php echo $item->title; ?>
				</span>
			</a>
            <time><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC')); ?></time>
		</li>
		<?php endforeach; ?>
	</ul>
</div>